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
class Controller_ProductImages extends \Controller_App {

    /**
     * List all ProductImages
     */
    public function action_list() {
        return \Bus\ProductImages_List::getInstance()->execute();
    }
    
    /**
     * Add or update ProductImages
     */
    public function action_addUpdate() {
        return \Bus\ProductImages_AddUpdate::getInstance()->execute();
    }
    
    /**
     * Add or disable ProductImages
     */
    public function action_disable() {
        return \Bus\ProductImages_Disable::getInstance()->execute();
    }
}
