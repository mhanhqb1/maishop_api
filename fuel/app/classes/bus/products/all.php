<?php

namespace Bus;

/**
 * Get all
 *
 * @package Bus
 * @created 2016-07-06
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class Products_All extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Product::get_all($data);
            return $this->result(\Model_Product::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
