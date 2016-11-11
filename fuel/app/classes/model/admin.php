<?php

use Fuel\Core\DB;
use Lib\Util;

/**
 * Any query in Model Admin.
 *
 * @package Model
 * @version 1.0
 * @author Le Tuan Tu
 * @copyright Oceanize INC
 */
class Model_Admin extends Model_Abstract {

    protected static $_properties = array(
        'id',
        'name',
        'login_id',
        'password',
        'admin_type',
        'disable',
        'created',
        'updated',
    );
    
    protected static $_observers = array(
//        'Model\Observer_Log' => array(
//            'events' => array(
//                'after_load',
//                'after_create',
//                'after_update',
//                'after_delete'
//            ),
//            'mysql_timestamp' => false,
//        ),
        'Orm\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => false,
        ),
        'Orm\Observer_UpdatedAt' => array(
            'events' => array('before_update'),
            'mysql_timestamp' => false,
        ),
    );
    
    protected static $_table_name = 'admins';
    
    /**
     * Login for admin.
     *
     * @author KienNH
     * @param array $param Input data.
     * @return array|bool Returns the array or the boolean.
     */
    public static function login($param) {
        $param['password'] = Util::encodePassword($param['password'], $param['login_id']);
        $query = DB::select(
                self::$_table_name . '.*'
            )
            ->from(self::$_table_name)
            ->where(self::$_table_name . '.disable', 0)
            ->where(self::$_table_name . '.login_id', '=', $param['login_id'])
            ->where(self::$_table_name . '.password', '=', $param['password']);
        $data = $query->execute(self::$slave_db)->offsetGet(0);
        
        return $data;
    }
    
    /**
     * Update password for admin.
     *
     * @author AnhMH
     * @param array $param Input data.
     * 
     * @return bool Returns the boolean.
     */
    public static function update_password($param)
    {
        $admin = self::find($param['id']);
        if ($admin) {
            $admin->set('password', Util::encodePassword($param['password'], $admin->get('login_id')));
            if ($admin->update()) {
                return true;
            }
        }
        self::errorNotExist('admin_id');
        return false;
    }
    
    /**
     * Add or update info for admin.
     *
     * @author AnhMH
     * @param array array $param Input data.
     * @return bool|int Returns the boolean or the integer.
     */
    public static function add_update($param)
    {
        $id = !empty($param['id']) ? $param['id'] : 0;
        $options['where'][] = array(
            'login_id' => $param['login_id'],
        );
        if (!empty($id)) {
            $admin = self::find($id);
            if (empty($admin)) {
                return false;
            }  
            $options['where'][] = array(
                'id', '<>', $id
            );
        }       
        $check = self::find('first', $options);             
        if (!empty($check) && $check->get('login_id') == $param['login_id']) {
            static::errorDuplicate('login_id', $param['login_id']);
            return false;
        }
        if (empty($admin)) {
            $admin = new self;  
        }
        if (isset($param['name'])) {
            $admin->set('name', $param['name']);
        }
        if (isset($param['login_id'])) {
            $admin->set('login_id', $param['login_id']);
        }
        if (isset($param['admin_type']) && $param['admin_type'] != '') {
            $admin->set('admin_type', $param['admin_type']);
        }
        if (isset($param['password'])) {
            $admin->set('password', Util::encodePassword($param['password'], $param['login_id']));
        }
        if ($admin->save()) {
            if (empty($admin->id)) {
                $admin->id = self::cached_object($admin)->_original['id'];
            }
            return !empty($admin->id) ? $admin->id : 0;
        }
        return false;
    }
    
    /**
     * Disable/Enable a admin.
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
                self::errorNotExist('admin_id');
                return false;
            }
        }
        return true;
    }
    
    /**
     * Get list admin
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
        if (!empty($param['login_id'])) {
            $query->where('login_id', 'LIKE', "%{$param['login_id']}%");
        }
        if (!empty($param['name'])) {
            $query->where('name', 'LIKE', "%{$param['name']}%");
        }
        if (isset($param['disable']) && $param['disable'] != '') {
            $query->where('disable', '=', $param['disable']);
        }
        if (isset($param['admin_type']) && $param['admin_type'] != '') {
            $query->where('admin_type', '=', $param['admin_type']);
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
     * Get detail of Admin
     * @param array $param
     * @return boolean false or array data
     */
    public static function get_detail($param) {
        if (empty($param['id']) || !is_numeric($param['id'])) {
            self::errorNotExist('id');
            return false;
        }
        
        $admin = self::find($param['id']);
        if (empty($admin)) {
            self::errorNotExist('id');
            return false;
        }
        $admin['login'] = !empty($admin['login_id']) ? $admin['login_id'] : '';
        return $admin;
    }
    
}
