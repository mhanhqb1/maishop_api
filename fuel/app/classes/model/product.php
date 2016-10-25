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
class Model_Product extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'cate_id',
        'price',
        'is_feature',
        'stock',
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
    protected static $_table_name = 'products';

    /**
     * 
     * @param type $param
     * @return boolean
     */
    public static function get_list($param) {
        $query = DB::select(
                self::$_table_name.'.id',
                self::$_table_name.'.price',
                self::$_table_name.'.cate_id',
                self::$_table_name.'.stock',
                self::$_table_name.'.is_feature',
                self::$_table_name.'.created',
                'product_informations.name',
                'product_informations.description',
                'product_informations.detail',
                'product_images.image'
            )
            ->from(self::$_table_name)
            ->join('product_informations', 'LEFT')
            ->on(self::$_table_name.'.id', '=', 'product_informations.product_id')
            ->join('product_images', 'LEFT')
            ->on(self::$_table_name.'.id', '=', 'product_images.product_id')
        ;

//        if (!empty($param['language_type'])) {
//            $query->where('product_information.language_type', '=', $param['language_type']);
//        }
        
        if (isset($param['disable'])) {
            $query->where(self::$_table_name.'.disable', '=', $param['disable']);
        }
        
        $data = $query->execute(self::$slave_db)->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;
        
        return array('total' => $total, 'data' => $data);
    }
    
    /**
     * 
     * @param type $param
     * @return boolean
     */
    public static function get_detail($param) {
        if (empty($param['id'])) {
            self::errorNotExist('product_id');
            return false;
        }
        $query = DB::select(
                self::$_table_name.'.id',
                self::$_table_name.'.price',
                self::$_table_name.'.cate_id',
                self::$_table_name.'.stock',
                self::$_table_name.'.is_feature',
                self::$_table_name.'.created',
                'product_informations.name',
                'product_informations.description',
                'product_informations.detail',
                'product_images.image'
            )
            ->from(self::$_table_name)
            ->join('product_informations', 'LEFT')
            ->on(self::$_table_name.'.id', '=', 'product_informations.product_id')
            ->join('product_images', 'LEFT')
            ->on(self::$_table_name.'.id', '=', 'product_images.product_id')
            ->where(self::$_table_name.'.id', $param['id'])
        ;

//        if (!empty($param['language_type'])) {
//            $query->where('product_information.language_type', '=', $param['language_type']);
//        }
        
        if (isset($param['disable'])) {
            $query->where(self::$_table_name.'.disable', '=', $param['disable']);
        }
        
        $data = $query->execute(self::$slave_db)->as_array();
        
        return !empty($data[0]) ? $data[0] : array();
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
                self::errorNotExist('product_id');
                return false;
            }
        }
        // set value
        if (!empty($param['price'])) {
            $self->set('price', $param['price']);
        }
        if (!empty($param['cate_id'])) {
            $self->set('cate_id', $param['cate_id']);
        }
        if (isset($param['is_feature'])) {
            $self->set('is_feature', $param['is_feature']);
        }
        if (isset($param['stock'])) {
            $self->set('stock', $param['stock']);
        }
        // save to database
        if ($self->save()) {
            if (empty($self->id)) {
                $self->id = self::cached_object($self)->_original['id'];
            }
            if (!empty($param['name']) || !empty($param['description']) || !empty($param['detail'])) {
                $param['product_id'] = $self->id;
                Model_Product_Information::add_update($param);
            }
            return !empty($self->id) ? $self->id : 0;
        }
        return false;
    }
    
}
