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
    
}
