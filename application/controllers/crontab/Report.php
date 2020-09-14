<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function import() {
        $this->_accesstrade_conversion();
    }

    public function _accesstrade_conversion() {
        
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d', strtotime("+1 days"));

        $this->load->library('accesstrade');
        $conversions = $this->accesstrade->conversion($fromDate, $toDate);

        $totalReward = $conversions['totalReward'];
        $totalConversionsCount = $conversions['totalConversionsCount'];

        foreach($conversions['conversionReportItems'] as $conversion) {

            $uid = 0;
            foreach($conversion['parameters'] as $parameter) {
                if($parameter['name'] != 'uid') continue;
                $uid = $parameter['value'];
            }

            $other_parameters = NULL;
            foreach($conversion as $field => $value) {
                $default_field = ['conversionId','siteId','siteName','campaignId','campaignName','creativeId','creativeName','verificationId','merchantCountryCode','publisherCountryCode','clickTime','conversionTime','confirmationTime','status','reward','transactionAmount','sessionId','parameters','products','customerType'];
                if(in_array($field, $default_field)) continue;

                $other_parameters[] = [$field => $value];
            }

            $this->load->model('report_conversion_model');
            $this->report_conversion_model->update_by_conversion_id(
                $conversion['conversionId'],
                'accesstrade',
                $uid,
                $conversion['siteId'],
                $conversion['siteName'],
                $conversion['campaignId'],
                $conversion['campaignName'],
                isset($conversion['customerType']) ? $conversion['customerType'] : NULL,
                $conversion['creativeId'],
                $conversion['creativeName'],
                $conversion['verificationId'],
                $conversion['clickTime'],
                $conversion['conversionTime'],
                !isset($conversion['confirmationTime']) ? NULL : $conversion['confirmationTime'],
                $conversion['status'],
                $conversion['reward'],
                $conversion['transactionAmount'],
                $conversion['sessionId'],
                json_encode($conversion['parameters']),
                (!empty($conversion['products'])) ? json_encode($conversion['products']) : NULL,
                (!empty($other_parameters)) ? json_encode($other_parameters) : NULL
            );
        }

        // print_r($conversions);
    }

}
