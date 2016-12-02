<?php

namespace Bus;

/**
 * Get product detail for cart
 *
 * @package Bus
 * @created 2016-12-01
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class Products_DetailForCart extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Product::get_detail_for_cart($data);
            return $this->result(\Model_Product::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
