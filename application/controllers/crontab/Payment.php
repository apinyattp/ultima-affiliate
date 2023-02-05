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

            $fromDate = date('Y-m-01', strtotime($payment["paymentDate"]));
            $toDate = date('Y-m-t', strtotime($payment["paymentDate"]));
            $periodBase = 'PAID_MONTH';
            $status = 'APPROVED';

            $a_conversion = $this->accesstrade->conversion($fromDate, $toDate, NULL, $status, $periodBase);
            foreach($a_conversion["conversionReportItems"] as $conversion) {
                $conversion_id = $conversion['conversionId'];
                $reward = $conversion['reward'];
                $transaction_amount = $conversion['transactionAmount'];
                // var_dump($conversion_id);

                $status = 'PAID';
                $time = date('Y-m-d H:i:s');

                $this->report_conversion_model->update_status($conversion_id, $status, $time, $reward, $transaction_amount);

            }
        }
    }

}
