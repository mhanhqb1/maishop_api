<?php

/**
 * Controller for actions on Version
 *
 * @package Controller
 * @created 2016-11-11
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Controller_Orders extends \Controller_App {

    /**
     * List all orders
     */
    public function action_list() {
        return \Bus\Orders_List::getInstance()->execute();
    }
    
    /**
     * Add or update order
     */
    public function action_addUpdate() {
        return \Bus\Orders_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Order detail
     */
    public function action_detail() {
        return \Bus\Orders_Detail::getInstance()->execute();
    }
    
    /**
     * Order disable
     */
    public function action_disable() {
        return \Bus\Orders_Disable::getInstance()->execute();
    }
    
    /**
     * Order all
     */
    public function action_all() {
        return \Bus\Orders_All::getInstance()->execute();
    }
}
