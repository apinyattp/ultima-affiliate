<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

    }

    public function accesstrade($fromMonth=NULL, $toMonth=NULL) {
        $this->load->library('provider/accesstrade');
        $this->load->model('report_conversion_model');

        if (!$fromMonth) {
            $fromMonth = date('Y-m');
        }
        if(!$toMonth){
            $toMonth = date('Y-m');
        }

        $a_payment = $this->accesstrade->payment($fromMonth, $toMonth);

        if(empty($a_payment)) return;

        foreach($a_payment as $payment) {
            var_dump($payment);
            if($payment['paidAmount'] <= 0) continue;

            $fromDate = $payment['rewardApprovedMonthPeriod']['from'].'-01';
            $_to = $payment['rewardApprovedMonthPeriod']['to'].'-01';
            $toDate = date('Y-m-t', strtotime($_to));
            $periodBase = 'CONVERSION_DATE ';
            $status = 'APPROVED';

            $a_conversion = $this->accesstrade->conversion($fromDate, $toDate, NULL, $status, $periodBase);
            foreach($a_conversion['conversionReportItems'] as $conversion) {
                $_conversion = $this->report_conversion_model->get_by_conversion_id($conversion['conversionId'], 'accesstrade');
                if(empty($_conversion)) continue;

                $reward = $_conversion['reward'];
                $transaction_amount = $_conversion['transaction_amount'];

                $status = 'PAID';
                $this->report_conversion_model->update_status($_conversion['id'], $status, $payment['paymentDate'], $reward, $transaction_amount);

                $this->_accesstrade_fix_apichanged($_conversion['verification_id'], $payment['paymentDate']);
            }
        }
    }

    public function _accesstrade_fix_apichanged($verification_id, $paymentDate) {
        $a_conversion = $this->report_conversion_model->list_by_verification_id($verification_id, 'accesstrade', 'APPROVED');
        foreach($a_conversion as $conversion) {
            $reward = $conversion['reward'];
            $transaction_amount = $conversion['transaction_amount'];
            $status = 'PAID';
            $this->report_conversion_model->update_status($conversion['id'], $status, $paymentDate, $reward, $transaction_amount);
        }
    }

}
