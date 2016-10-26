<?php

/**
 * Controller for actions on report
 *
 * @package Controller
 * @created 2016-10-13
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Controller_Reports extends \Controller_App {
    
    /**
     *  Get list commont report data
     */
    public function action_general() {
        return \Bus\Reports_General::getInstance()->execute();
    }
    
    /**
     *  Get list commont report data
     */
    public function action_export() {
        return \Bus\Reports_Export::getInstance()->execute();
    }
    
}
