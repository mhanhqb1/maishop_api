<?php

namespace Bus;

/**
 * Add and update info for Order
 *
 * @package Bus
 * @created 2016-11-11
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class Orders_List extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Order::get_list($data);
            return $this->result(\Model_Order::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
