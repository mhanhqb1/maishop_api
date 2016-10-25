<?php

namespace Bus;

/**
 * Disable/Enable Admin
 *
 * @package Bus
 * @created 2016-10-18
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Admins_Disable extends BusAbstract
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
     * call function disable() from model Admin
     *
     * @created 2016-10-18
     * @author AnhMH
     * @param $data
     * @return bool
     * @example
     */
    public function operateDB($data)
    {
        try {
            $this->_response = \Model_Admin::disable($data);
            return $this->result(\Model_Admin::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
