<?php

use Fuel\Core\DB;

/**
 * Any query in Model Report
 *
 * @package Model
 * @created 2016-10-13
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Model_Report extends Model_Abstract {

    /**
     * Get general
     *
     * @author KienNH
     * @param array $param Input data.
     * @return array Returns the array.
     */
    public static function general($param) {
        ini_set('memory_limit', -1);
        
        if (!is_numeric($param['report_date'])) {
            $param['report_date'] = strtotime($param['report_date']);
        }
        $this_month = date('Y-m', $param['report_date']);
        
        $count_order_in_month = DB::select(
                DB::expr("COUNT(id) AS cnt")
            )
            ->from('orders')
            ->where('disable', '0')
            ->where(DB::expr("FROM_UNIXTIME(created, '%Y-%m') = '{$this_month}'"))
            ->execute(self::$slave_db)
            ->offsetGet(0);
        
        $count_order = DB::select(
                DB::expr("COUNT(id) AS cnt")
            )
            ->from('orders')
            ->where('disable', '0')
            ->execute(self::$slave_db)
            ->offsetGet(0);
        
        $count_price_in_month = DB::select(
                DB::expr("SUM(IFNULL(total_price, 0)) AS cnt")
            )
            ->from('orders')
            ->where('orders.disable', '0')
            ->where(DB::expr("FROM_UNIXTIME(orders.created, '%Y-%m') = '{$this_month}'"))
            ->execute(self::$slave_db)
            ->offsetGet(0);
        
        $count_price = DB::select(
                DB::expr("SUM(IFNULL(total_price, 0)) AS cnt")
            )
            ->from('orders')
            ->where('orders.disable', '0')
            ->execute(self::$slave_db)
            ->offsetGet(0);
        
        if (!empty($param['get_new_orders'])) {
            $new_orders = DB::select(
                    'orders.*'
                )
                ->from('orders')
                ->where('orders.disable', '0')
                ->order_by('orders.created', 'DESC')
                ->limit(10)
                ->execute(self::$slave_db)
                ->as_array();
        }
        
        if (!empty($param['get_new_products'])) {
            $new_products = DB::select(
                    'products.*',
                    'product_informations.name',
                    'product_images.image'
                )
                ->from('products')
                ->join('product_informations', 'LEFT')
                ->on('product_informations.product_id', '=', 'products.id')
                ->join('product_images', 'LEFT')
                ->on('product_images.product_id', '=', 'products.id')
                ->where('products.disable', '0')
                ->order_by('products.created', 'DESC')
                ->limit(10)
                ->execute(self::$slave_db)
                ->as_array();
        }
        
        $item = array(
            'count_order_in_month' => !empty($count_order_in_month['cnt']) ? $count_order_in_month['cnt'] : 0,
            'count_order' => !empty($count_order['cnt']) ? $count_order['cnt'] : 0,
            'count_price_in_month' => !empty($count_price_in_month['cnt']) ? $count_price_in_month['cnt'] : 0,
            'count_price' => !empty($count_price['cnt']) ? $count_price['cnt'] : 0,
            'products' => !empty($new_products) ? $new_products : array(),
            'orders' => !empty($new_orders) ? $new_orders : array(),
        );
        return $item;
    }
    
    /**
     * Get data for export excel
     * 
     * @param array $param
     * @return boolean|array
     */
    public static function export($param) {
        ini_set('memory_limit', -1);
        
        // Validate date from/to
        if (!empty($param['date_to']) && !empty($param['date_from']) && $param['date_to'] < $param['date_from']) {
            self::errorOther(self::ERROR_CODE_OTHER_1, 'date_to');
            return false;
        }
        
        // Init
        $limit = 2000;
        
        // Get Itemsets
        $itemsets = DB::select(
                array('itemsets.id',                                'ID'),
                array('itemsets.name',                              'Name'),
                array('itemsets.price',                             'Price'),
                array('itemsets.stock',                             'Stock'),
                array('itemsets.disable',                           'Disable'),
                DB::expr("FROM_UNIXTIME(itemsets.created, '%Y-%m-%d %H:%i') AS Created"),
                DB::expr("FROM_UNIXTIME(itemsets.updated, '%Y-%m-%d %H:%i') AS Updated")
            )
            ->from('itemsets')
            ->where('disable', 0)
            ->order_by('id', 'DESC')
            ->limit($limit)
            ->execute(self::$slave_db)
            ->as_array();
        
        // Get Orders
        $query = DB::select(
                array('orders.id',                                  'ID'),
                array('orders.itemset_id',                          'Itemset ID'),
                array('orders.payment_status',                      'Payment Status'),
                array('orders.charge_response_id',                  'Charge Response ID'),
                array('orders.user_name',                           'User Name'),
                array('orders.user_email',                          'User Email'),
                array('orders.user_tel',                            'User Tel'),
                array('orders.user_postcode',                       'User Postcode'),
                array('orders.user_address1',                       'User Address1'),
                array('orders.user_address2',                       'User Address2'),
                array('orders.ship_name',                           'Ship Name'),
                array('orders.ship_postcode',                       'Ship Postcode'),
                array('orders.ship_address1',                       'Ship Address1'),
                array('orders.ship_address2',                       'Ship Address2'),
                array('orders.disable',                             'Disable'),
                DB::expr("FROM_UNIXTIME(orders.created, '%Y-%m-%d %H:%i') AS Created"),
                DB::expr("FROM_UNIXTIME(orders.updated, '%Y-%m-%d %H:%i') AS Updated")
            )
            ->from('orders')
            ->where('disable', 0);
        
        if (!empty($param['date_from'])) {
            $query->where(DB::expr("FROM_UNIXTIME(orders.created, '%Y-%m-%d %H:%i')"), '>=', $param['date_from']);
        }
        if (!empty($param['date_to'])) {
            $query->where(DB::expr("FROM_UNIXTIME(orders.created, '%Y-%m-%d %H:%i')"), '<=', $param['date_to']);
        }
        $orders = $query->order_by('id', 'DESC')
            ->limit($limit)
            ->execute(self::$slave_db)
            ->as_array();
        
        $charge_responses_ids = Lib\Arr::field($orders, 'Charge Response ID');
        
        // Get charge_responses
        $charge_responses = DB::select(
                array('charge_responses.id',                        'ID'),
                array('charge_responses.response_id',               'Response ID'),
                array('charge_responses.object',                    'Object'),
                array('charge_responses.livemode',                  'Livemode'),
                array('charge_responses.currency',                  'Currency'),
                array('charge_responses.description',               'Description'),
                array('charge_responses.amount',                    'Amount'),
                array('charge_responses.amountRefunded',            'AmountRefunded'),
                array('charge_responses.customer',                  'Customer'),
                array('charge_responses.recursion',                 'Recursion'),
                DB::expr("FROM_UNIXTIME(charge_responses.created, '%Y-%m-%d %H:%i') AS Created"),
                array('charge_responses.paid',                      'Paid'),
                array('charge_responses.refunded',                  'Refunded'),
                array('charge_responses.failureMessage',            'FailureMessage'),
                array('charge_responses.card_expYear',              'Card ExpYear'),
                array('charge_responses.card_expMonth',             'Card ExpMonth'),
                array('charge_responses.card_fingerprint',          'Card Fingerprint'),
                array('charge_responses.card_name',                 'Card Name'),
                array('charge_responses.card_country',              'Card Country'),
                array('charge_responses.card_type',                 'Card Type'),
                array('charge_responses.card_cvcCheck',             'Card CVC Check'),
                array('charge_responses.card_last4',                'Card Last 4'),
                array('charge_responses.captured',                  'Captured'),
                array('charge_responses.expireTime',                'ExpireTime'),
                array('charge_responses.fees_transactionType',      'Fees TransactionType'),
                array('charge_responses.fees_transactionFee',       'Fees TransactionFee'),
                array('charge_responses.fees_rate',                 'Fees Rate'),
                array('charge_responses.fees_amount',               'Fees Amount'),
                array('charge_responses.fees_created',              'Fees Created')
            )
            ->from('charge_responses')
            ->where('charge_responses.id', 'IN', $charge_responses_ids)
            ->order_by('id', 'DESC')
            ->execute(self::$slave_db)
            ->as_array();
        
        return array(
            'itemsets' => $itemsets,
            'orders' => $orders,
            'charge_responses' => $charge_responses,
        );
    }

}
