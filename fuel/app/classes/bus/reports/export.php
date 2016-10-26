<?php

namespace Bus;

/**
 * Get data for export excel
 *
 * @package Bus
 * @created 2016-10-18
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Reports_Export extends BusAbstract {
    
    /** @var array Array of parameter's date format */
    protected $_date_format = array(
        'date_from' => 'Y-m-d',
        'date_to' => 'Y-m-d',
    );
    
    /**
     * Get data for export excel
     */
    public function operateDB($data) {
        try {
            $this->_response = \Model_Report::export($data);
            return $this->result(\Model_Report::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
