<?php

/**
 * Controller for actions on Version
 *
 * @package Controller
 * @created 2016-08-31
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Controller_Products extends \Controller_App {

    /**
     * List all products
     */
    public function action_list() {
        return \Bus\Products_List::getInstance()->execute();
    }
    
    /**
     * Add or update product
     */
    public function action_addUpdate() {
        return \Bus\Products_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Product detail
     */
    public function action_detail() {
        return \Bus\Products_Detail::getInstance()->execute();
    }
    
    /**
     * Product diable
     */
    public function action_disable() {
        return \Bus\Products_Disable::getInstance()->execute();
    }
}
