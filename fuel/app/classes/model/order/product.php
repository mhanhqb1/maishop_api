<?php

use Fuel\Core\DB;

/**
 * Any query in Model Version
 *
 * @package Model
 * @created 2016-11-11
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Model_Order_Product extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'order_id',
        'product_id',
        'qty',
        'created',
        'updated'
    );
    
    protected static $_observers = array(
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );

    /** @var array $_table_name name of table */
    protected static $_table_name = 'order_products';

    /**
     * Add or update
     * @param type $param
     * @return boolean
     */
    public static function add_update($param) {
        $id = !empty($param['id']) ? $param['id'] : 0;
        $self = new self;
        // check exist
        if (!empty($id)) {
            $self = self::find($id);
            if (empty($self)) {
                self::errorNotExist('order_product_id');
                return false;
            }
        }
        // set value
        if (!empty($param['order_id'])) {
            $self->set('order_id', $param['order_id']);
        }
        if (!empty($param['product_id'])) {
            $self->set('product_id', $param['product_id']);
        }
        if (!empty($param['qty'])) {
            $self->set('qty', $param['qty']);
        }
        // save to database
        if ($self->save()) {
            if (empty($self->id)) {
                $self->id = self::cached_object($self)->_original['id'];
            }
            return !empty($self->id) ? $self->id : 0;
        }
        return false;
    }
    
    /**
     * Get list
     *
     * @author AnhMH
     * @param array array $param Input data.
     * @return array Returns array(total, data).
     */
    public static function get_list($param)
    {
        $query = DB::select(
                    self::$_table_name . '.*'
                )
                ->from(self::$_table_name);        
        if (!empty($param['order_id'])) {
            $query->where('order_id', '=', $param['order_id']);
        }
        
        if (!empty($param['sort'])) {
            if (!self::checkSort($param['sort'])) {
                self::errorParamInvalid('sort');
                return false;
            }

            $sortExplode = explode('-', $param['sort']);
            if ($sortExplode[0] == 'created') {
                $sortExplode[0] = self::$_table_name . '.created';
            }
            $query->order_by($sortExplode[0], $sortExplode[1]);
        } else {
            $query->order_by(self::$_table_name . '.created', 'DESC');
        }
        if (!empty($param['page']) && !empty($param['limit'])) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
        }
        $data = $query->execute(self::$slave_db)->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;

        return array($total, $data);
    }
    
    /**
     * Get all
     *
     * @author AnhMH
     * @param array array $param Input data.
     * @return array Returns array(total, data).
     */
    public static function get_all($param)
    {
        $query = DB::select(
                    self::$_table_name . '.*',
                    array('product_informations.name', 'product_name'),
                    array('product_informations.description', 'product_description'),
                    array('products.price', 'product_price'),
                    array('product_images.image', 'product_image')
                )
                ->from(self::$_table_name)
                ->join('products', 'LEFT')
                ->on('products.id', '=', self::$_table_name.'.product_id')
                ->join('product_informations', 'LEFT')
                ->on('product_informations.product_id', '=', self::$_table_name.'.product_id')
                ->join(DB::expr("(SELECT image, product_id FROM product_images GROUP BY product_id ORDER BY is_default DESC) AS product_images"), 'LEFT')
                ->on('product_images.product_id', '=', self::$_table_name.'.product_id')
        ;
        
        if (!empty($param['order_id'])) {
            $query->where('order_id', '=', $param['order_id']);
        }
        
        if (!empty($param['sort'])) {
            if (!self::checkSort($param['sort'])) {
                self::errorParamInvalid('sort');
                return false;
            }
            $sortExplode = explode('-', $param['sort']);
            if ($sortExplode[0] == 'created') {
                $sortExplode[0] = self::$_table_name . '.created';
            }
            $query->order_by($sortExplode[0], $sortExplode[1]);
        } else {
            $query->order_by(self::$_table_name . '.created', 'DESC');
        }
        if (!empty($param['page']) && !empty($param['limit'])) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
        }
        $data = $query->execute(self::$slave_db)->as_array();

        return $data;
    }
}
