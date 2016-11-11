<?php

use Fuel\Core\DB;

/**
 * Any query in Model Version
 *
 * @package Model
 * @created 2016-08-31
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Model_Order extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'phone',
        'note',
        'address',
        'district_id',
        'province_id',
        'created',
        'updated',
        'total_price',
        'is_paid',
        'is_cancel',
        'disable',
        'user_id',
        'is_deposit',
        'deposit_money'
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
    protected static $_table_name = 'orders';

    /**
     * 
     * @param type $param
     * @return boolean
     */
    public static function get_list($param) {
        $query = DB::select(
                self::$_table_name.'.*',
                array('provinces.name', 'provinces_name'),
                array('districts.name', 'districts_name')
            )
            ->from(self::$_table_name)
            ->join('provinces', 'LEFT')
            ->on('provinces.provinceid', '=', self::$_table_name.'.province_id')
            ->join('districts', 'LEFT')
            ->on('districts.districtid', '=', self::$_table_name.'.district_id')
        ;
        if (!empty($param['user_name'])) {
            $query->where(self::$_table_name.'.user_name', $param['user_name']);
        }
        if (!empty($param['district_id'])) {
            $query->where(self::$_table_name.'.district_id', $param['district_id']);
        }
        if (!empty($param['province_id'])) {
            $query->where(self::$_table_name.'.province_id', $param['province_id']);
        }
        if (isset($param['disable'])) {
            $query->where(self::$_table_name.'.disable', $param['disable']);
        }
        if (isset($param['is_paid'])) {
            $query->where(self::$_table_name.'.is_paid', $param['is_paid']);
        }
        if (isset($param['is_cancel'])) {
            $query->where(self::$_table_name.'.is_cancel', $param['is_cancel']);
        }
        if (isset($param['is_deposit'])) {
            $query->where(self::$_table_name.'.is_deposit', $param['is_deposit']);
        }
        if (!empty($param['sort'])) {
            $sortExplode = explode('-', $param['sort']);
            $query->order_by($sortExplode[0], $sortExplode[1]);
        } else {
            $query->order_by(self::$_table_name . '.created', 'DESC');
        }
        $query->group_by(self::$_table_name.'.id');
        $data = $query->execute(self::$slave_db)->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;
        
        return array('total' => $total, 'data' => $data);
    }
    
    /**
     * Disable/Enable a order.
     *
     * @author AnhMH
     * @param array array $param Input data.
     * @return bool Returns the boolean.
     */
    public static function disable($param)
    {
        if (!isset($param['disable'])) {
            return false;
        }
        $ids = explode(',', $param['id']);
        foreach ($ids as $id) {
            $admin = self::find($id);
            if ($admin) {
                $admin->set('disable', $param['disable']);
                if (!$admin->save()) {
                    return false;
                }
            } else {
                self::errorNotExist('order_id');
                return false;
            }
        }
        return true;
    }
    
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
                self::errorNotExist('order_id');
                return false;
            }
        }
        // set value
        if (!empty($param['phone'])) {
            $self->set('phone', $param['phone']);
        }
        if (!empty($param['note'])) {
            $self->set('note', $param['note']);
        }
        if (!empty($param['address'])) {
            $self->set('address', $param['address']);
        }
        if (!empty($param['district_id'])) {
            $self->set('district_id', $param['district_id']);
        }
        if (!empty($param['province_id'])) {
            $self->set('province_id', $param['province_id']);
        }
        if (!empty($param['user_name'])) {
            $self->set('user_name', $param['user_name']);
        }
        if (!empty($param['deposit_money'])) {
            $self->set('deposit_money', $param['deposit_money']);
            $self->set('is_deposit', 1);
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
