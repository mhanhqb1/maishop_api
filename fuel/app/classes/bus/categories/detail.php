<?php

namespace Bus;

/**
 * Get detail
 *
 * @package Bus
 * @created 2016-11-29
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class Categories_Detail extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Category::get_detail($data);
            return $this->result(\Model_Category::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
