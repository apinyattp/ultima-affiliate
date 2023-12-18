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
        $this->load->model('payment_model');
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
            $invoice_no = $payment['invoiceNumber'];
            if (empty($invoice_no)) continue;

            $payment_db = $this->payment_model->get_by_invoice_no($invoice_no, 'accesstrade');
            if(!empty($payment_db)) continue;

            $payment_id = $this->payment_model->insert(
                $invoice_no,
                'accesstrade',
                $payment['paidAmount'],
                $payment['totalAmount'],
                $payment['vat'],
                $payment['wht'],
                $payment['paymentDate'],
            );

            if($payment['totalAmount'] <= 0) continue;
            $amount_total = $payment['totalAmount'];
            $amount_member = 0;

            $fromDate = $payment['rewardApprovedMonthPeriod']['from'].'-01';
            $_to = $payment['rewardApprovedMonthPeriod']['to'].'-01';
            $toDate = date('Y-m-t', strtotime($_to));
            $periodBase = 'CONVERSION_DATE';
            $status = 'APPROVED';

            $a_conversion = $this->accesstrade->conversion($fromDate, $toDate, NULL, $status, $periodBase);
            foreach($a_conversion['conversionReportItems'] as $conversion) {
                $_conversion = $this->report_conversion_model->get_by_conversion_id($conversion['conversionId'], 'accesstrade');
                if(empty($_conversion)) continue;

                $reward = $_conversion['reward'];
                $transaction_amount = $_conversion['transaction_amount'];

                if ($reward > $amount_total) continue;
                $amount_total -= $reward;
                $amount_member += $reward;

                $status = 'PAID';
                $this->report_conversion_model->update_status($_conversion['id'], $status, $payment['paymentDate'], $reward, $transaction_amount);

                $this->_accesstrade_fix_apichanged($_conversion['verification_id'], $payment['paymentDate']);
            }
            $this->payment_model->update_amount_member($payment_id, $amount_member);
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
