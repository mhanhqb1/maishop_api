<?php

namespace Bus;

/**
 * update password for admin
 *
 * @package Bus
 * @created 2016-10-18
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Admins_UpdatePassword extends BusAbstract
{
    protected $_required = array(
        'id',
        'password'
    );

    protected $_length = array(
        'password' => array(6, 40),
    );

    /**
     * call function update_password() from model Admins
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
            $this->_response = \Model_Admin::update_password($data);
            return $this->result(\Model_Admin::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
