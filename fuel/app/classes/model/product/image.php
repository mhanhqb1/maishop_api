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
class Model_Product_Image extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'product_id',
        'image',
        'is_default',
        'created',
        'updated',
        'disable'
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
    protected static $_table_name = 'product_images';

    /**
     * 
     * @param type $param
     * @return boolean
     */
    public static function get_list($param) {
        $query = DB::select(
                self::$_table_name.'.id',
                self::$_table_name.'.product_id',
                self::$_table_name.'.image',
                self::$_table_name.'.is_default',
                self::$_table_name.'.created',
                self::$_table_name.'.disable',
                'product_informations.name'
            )
            ->from(self::$_table_name)
            ->join('product_informations', 'LEFT')
            ->on('product_informations.product_id', '=', self::$_table_name.'.product_id')
        ;
        
        if (!empty($param['product_id'])) {
            $query->where(self::$_table_name.'.product_id', '=', $param['product_id']);
        }
        
        if (isset($param['is_default'])) {
            $query->where(self::$_table_name.'.is_default', '=', $param['is_default']);
        }
        
        if (!empty($param['name'])) {
            $query->where('product_informations.name', 'LIKE', "%{$param['name']}%");
        }
        
        if (isset($param['disable'])) {
            $query->where(self::$_table_name.'.disable', '=', $param['disable']);
        }
        
        $query->group_by(self::$_table_name.'.id');
        if (!empty($param['page']) && !empty($param['limit'])) {
            $offset = ($param['page'] - 1) * $param['limit'];
            $query->limit($param['limit'])->offset($offset);
        }
        $query->order_by(self::$_table_name.'.created', 'DESC');
        $data = $query->execute(self::$slave_db)->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;
        
        return array('total' => $total, 'data' => $data);
    }
    
    /**
     * 
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
                self::errorNotExist('product_image_id');
                return false;
            }
        } else {
            if (empty($param['product_id'])
                || empty($param['image'])) {
                self::errorParamInvalid();
                return false;
            }
        }
        
        // Upload images
        $imagePath = array();
        if (!empty($_FILES) && empty($param['image'])) {
            $uploadResult = \Lib\Util::uploadImage($thumb = 'places');            
            if ($uploadResult['status'] != 200) {
                self::setError($uploadResult['error']);            
                return false;
            }
            $param['image'] = $uploadResult['body'];
        }
        
        // set value
        if (!empty($param['product_id'])) {
            $self->set('product_id', $param['product_id']);
        }
        if (!empty($param['image'])) {
            $self->set('image', $param['image']);
        }
        if (isset($param['is_default'])) {
            $self->set('is_default', $param['is_default']);
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
     * Disable/Enable a product image.
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
                self::errorNotExist('product_image_id');
                return false;
            }
        }
        return true;
    }
    
}
