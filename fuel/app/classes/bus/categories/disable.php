<?php

namespace Bus;

/**
 * Disable/Enable
 *
 * @package Bus
 * @created 2016-11-29
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Categories_Disable extends BusAbstract
{
    // check require
    protected $_required = array(
        'id',
        'disable',
    );

    // check number
    protected $_number_format = array(
        'disable'
    );

    // check length
    protected $_length = array(
        'disable' => 1
    );

    /**
     * call function disable() from model category
     *
     * @created 2016-11-29
     * @author AnhMH
     * @param $data
     * @return bool
     * @example
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Category::disable($data);
            return $this->result(\Model_Category::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
