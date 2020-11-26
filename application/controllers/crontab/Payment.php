<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

    }

    public function accesstrade() {
        $this->load->library('accesstrade');
        $this->load->model('report_conversion_model');

        $fromMonth = date('Y-m');
        $toMonth = date('Y-m');

        $a_payment = $this->accesstrade->payment($fromMonth, $toMonth);

        if(empty($a_payment)) return;

        foreach($a_payment as $payment) {
            if($payment['status'] == 'UNPAID') break;

            $fromDate = date('Y-m-01', strtotime($payment['rewardApprovedMonth']));
            $toDate = date('Y-m-t', strtotime($payment['rewardApprovedMonth']));
            $periodBase = 'PAID_MONTH';
            $status = 'APPROVED';

            $a_conversion = $this->accesstrade->conversion($fromDate, $toDate, NULL, $status, $periodBase);

            foreach($a_conversion as $conversion) {

                $conversion_id = $conversion['conversionId'];
                $reward = $conversion['reward'];
                $transaction_amount = $conversion['transactionAmount'];

                $status = 'PAID';
                $time = date('Y-m-d H:i:s');

                $this->report_conversion_model->update_status($conversion_id, $status, $time, $reward, $transaction_amount);

            }
        }
    }

}
