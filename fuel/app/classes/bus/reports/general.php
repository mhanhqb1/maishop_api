<?php

namespace Bus;

/**
 * Get general report
 *
 * @package Bus
 * @created 2016-10-13
 * @version 1.0
 * @author KienNH
 * @copyright Oceanize INC
 */
class Reports_General extends BusAbstract {
    
    /**
     * Get general report
     */
    public function operateDB($data) {
        try {
            if (empty($data['report_date'])) {
                $data['report_date'] = time();
            }
            $this->_response = \Model_Report::general($data);
            return $this->result(\Model_Report::error());
        } catch (\Exception $e) {
            $this->_exception = $e;
        }
        return false;
    }

}
