<?php

/**
 * Controller_Test
 *
 * @package Controller
 * @created 2016-08-31
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Controller_Test extends \Controller_Rest {

    /**
     * 
     */
    public function action_index() {
        echo '<pre>';
        $param = array(
//            'name' => 'update',
//            'price_from' => 100,
//            'price_to' => 150
            'id' => 7
        );
        $data = Model_Product::get_detail($param);
        print_r($data);exit();
        echo date('Y-m-d H:i:s');
        echo '<br/>';
        echo date_default_timezone_get();
        exit;
    }
    
    /**
     * Show PHP info
     */
    public function action_phpinfo() {
        phpinfo();
        exit;
    }
    
}
