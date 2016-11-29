<?php

namespace Bus;

/**
 * Add and update category
 *
 * @package Bus
 * @created 2016-11-29
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class Categories_AddUpdate extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Category::add_update($data);
            return $this->result(\Model_Category::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
