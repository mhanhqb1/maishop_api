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
class Model_District extends Model_Abstract {

    /** @var array $_properties field of table */
    protected static $_properties = array(
        'districtid',
        'name',
        'type',
        'location',
        'provinceid'
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
    protected static $_table_name = 'districts';

    /**
     * 
     * @param type $param
     * @return boolean
     */
    public static function get_list($param) {
        $query = DB::select(
                self::$_table_name.'.districtid',
                self::$_table_name.'.name',
                self::$_table_name.'.provinceid'
            )
            ->from(self::$_table_name)
        ;

        if (!empty($param['province_id'])) {
            $query->where(self::$_table_name.'.provinceid', '=', $param['province_id']);
        }
        
        $data = $query->execute(self::$slave_db)->as_array();
        $total = !empty($data) ? DB::count_last_query(self::$slave_db) : 0;
        
        return array('total' => $total, 'data' => $data);
    }
    
}
