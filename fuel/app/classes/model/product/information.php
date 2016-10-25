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
class Model_Product_Information extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'id',
        'product_id',
        'name',
        'description',
        'detail',
        'language_type',
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
    protected static $_table_name = 'product_informations';

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
    public static function add_update($param) {
        $id = !empty($param['product_id']) ? $param['product_id'] : 0;
        // check exist
        if (!empty($param['product_id'])) {
            $self = self::find('first', array(
                    'where' => array(
                        'product_id' => $param['product_id'],
//                        'language_type' => $param['language_type'],
                    )
                )
            );
        } elseif (!empty($param['id'])) {
            $self = self::find($param['id']);
            if (empty($self)) {
                self::errorNotExist('product_information_id');
                return false;
            }                       
        }
        if (empty($self)) {
            $self = new self;
        }
        if (!empty($param['product_id'])) {
            $self->set('product_id', $param['product_id']);
        }
        if (!empty($param['name'])) {
            $self->set('name', $param['name']);
        }
        if (!empty($param['description'])) {
            $self->set('description', $param['description']);
        }
        if (!empty($param['detail'])) {
            $self->set('detail', $param['detail']);
        }
        if (!empty($param['language_type'])) {
            $self->set('language_type', $param['language_type']);
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
