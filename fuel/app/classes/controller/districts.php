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
class Controller_Districts extends \Controller_App {

    /**
     * Check current version for updating app
     */
    public function action_list() {
        return \Bus\Districts_List::getInstance()->execute();
    }

}
