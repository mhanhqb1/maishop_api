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
class Orders_All extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Order::get_all($data);
            return $this->result(\Model_Order::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
