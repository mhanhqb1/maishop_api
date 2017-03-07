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
class Model_Category extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'name',
        'image_path',
        'position',
        'disable',
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
    protected static $_table_name = 'categories';
    
    /**
     * Get all
     * @param type $param
     * @return boolean
     */
    public static function get_all($param) {
        $query = DB::select(
                self::$_table_name.'.id',
                self::$_table_name.'.image_path',
                self::$_table_name.'.name',
                self::$_table_name.'.position'
            )
            ->from(self::$_table_name)
            ->where(self::$_table_name . '.disable', '=', 0)
        ;
        
        if (!empty($param['name'])) {
            $query->where(self::$_table_name.'.name', 'LIKE', $param['name']);
        }
        if (!empty($param['page']) && !empty($param['limit'])) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
        }
         if (!empty($param['sort'])) {
            $sortExplode = explode('-', $param['sort']);
            $query->order_by($sortExplode[0], $sortExplode[1]);
        } else {
            $query->order_by(self::$_table_name . '.position', 'ASC');
        }
        $query->group_by(self::$_table_name.'.id');
        $data = $query->execute(self::$slave_db)->as_array();
        
        return $data;
    }
    
    /**
     * Get list
     * @param type $param
     * @return array
     */
    public static function get_list($param) {
        $query = DB::select(
                self::$_table_name.'.id',
                self::$_table_name.'.image_path',
                self::$_table_name.'.name',
                self::$_table_name.'.position',
                self::$_table_name.'.disable',
                self::$_table_name.'.created'
            )
            ->from(self::$_table_name)
        ;
        
        if (!empty($param['name'])) {
            $query->where(self::$_table_name.'.name', 'LIKE', "%{$param['name']}%");
        }
        
        if (isset($param['disable'])) {
            $query->where(self::$_table_name.'.disable', '=', $param['disable']);
        }
        if (!empty($param['page']) && !empty($param['limit'])) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
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
     * Get detail
     * @param type $param
     * @return array
     */
    public static function get_detail($param) {
        if (empty($param['id'])) {
            self::errorNotExist('category_id');
            return false;
        }
        $data = self::find('first', array(
            'where' => array(
                'id' => $param['id']
            )
        ));
        return $data;
    }
    
    /**
     * Add update
     * @param type $param
     * @return int
     */
    public static function add_update($param) {
        $id = !empty($param['id']) ? $param['id'] : 0;
        $self = new self;
        // check exist
        if (!empty($id)) {
            $self = self::find($id);
            if (empty($self)) {
                self::errorNotExist('category_id');
                return false;
            }
        }
        // set value
        if (!empty($param['name'])) {
            $self->set('name', $param['name']);
        }
        // Upload images
        $imagePath = array();
        if (!empty($_FILES)) {
            $uploadResult = \Lib\Util::uploadImage();            
            if ($uploadResult['status'] != 200) {
                self::setError($uploadResult['error']);            
                return false;
            }
            $param['image_path'] = $uploadResult['body'];
        }
        if (!empty($param['image_path'])) {
            $self->set('image_path', $param['image_path']);
        }
        if (isset($param['position'])) {
            $self->set('position', $param['position']);
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
     * Disable/Enable.
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
                self::errorNotExist('category_id');
                return false;
            }
        }
        return true;
    }
}
