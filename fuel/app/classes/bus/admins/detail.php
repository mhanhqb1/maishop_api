<?php

namespace Bus;

/**
 * get detail admin
 *
 * @package Bus
 * @created 2016-10-18
 * @version 1.0
 * @author AnhMH
 * @copyright Oceanize INC
 */
class Admins_Detail extends BusAbstract
{
    // check number
    protected $_number_format = array(
        'id'
    );

    // check length
    protected $_length = array(
        'id' => array(1, 11),
        'login_id' => array(0, 40),
        'password' => array(0, 40)
    );

    /**
     * get detail admin by id or login_id
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
            $this->_response = \Model_Admin::get_detail($data);
            return $this->result(\Model_Admin::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
