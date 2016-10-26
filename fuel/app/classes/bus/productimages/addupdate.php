<?php

namespace Bus;

/**
 * Add and update info for User
 *
 * @package Bus
 * @created 2016-07-06
 * @version 1.0
 * @author AnhNH
 * @copyright Oceanize INC
 */
class ProductImages_AddUpdate extends BusAbstract {

    public function operateDB($data) {
        try {
            $this->_response = \Model_Product_Image::add_update($data);
            return $this->result(\Model_Product_Image::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
