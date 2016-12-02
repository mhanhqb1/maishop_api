<?php

/**
 * Controller for actions on Version
 *
 * @package Controller
 * @created 2016-11-29
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Controller_Categories extends \Controller_App {

    /**
     * List all categories
     */
    public function action_list() {
        return \Bus\Categories_List::getInstance()->execute();
    }
    
    /**
     * Add or update product
     */
    public function action_addUpdate() {
        return \Bus\Categories_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Category detail
     */
    public function action_detail() {
        return \Bus\Categories_Detail::getInstance()->execute();
    }
    
    /**
     * Category diable
     */
    public function action_disable() {
        return \Bus\Categories_Disable::getInstance()->execute();
    }
    
    /**
     * Category all
     */
    public function action_all() {
        return \Bus\Categories_All::getInstance()->execute();
    }
}
