<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function import() {
        $this->accesstrade_conversion();
        // $this->admitad_conversion();
    }

    public function accesstrade_conversion() {
        
        $fromDate = date('Y-m-d');
        $toDate = date('Y-m-d', strtotime("+1 days"));

        $this->load->library('accesstrade');
        $conversions = $this->accesstrade->conversion($fromDate, $toDate);

        if(empty($conversions)) return TRUE;

        $totalReward = $conversions['totalReward'];
        $totalConversionsCount = $conversions['totalConversionsCount'];

        $this->load->model('report_conversion_model');
        foreach($conversions['conversionReportItems'] as $conversion) {

            $uid = 0;
            foreach($conversion['parameters'] as $parameter) {
                if($parameter['name'] != 'uid') continue;
                $uid = $parameter['value'];
            }

            $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion['conversionId']);
            if($conversion['status'] != 'PENDING' && !empty($a_conversion)) {
                $this->report_conversion_model->update_status(
                    $conversion['conversionId'], 
                    $conversion['status'], 
                    !isset($conversion['confirmationTime']) ? NULL : $conversion['confirmationTime'],
                    $conversion['reward'], 
                    $conversion['transactionAmount']
                );
    
                continue;
            }

            $other_parameters = NULL;
            foreach($conversion as $field => $value) {
                $default_field = ['conversionId','siteId','siteName','campaignId','campaignName','creativeId','creativeName','verificationId','merchantCountryCode','publisherCountryCode','clickTime','conversionTime','confirmationTime','status','reward','transactionAmount','sessionId','parameters','products','customerType'];
                if(in_array($field, $default_field)) continue;

                $other_parameters[] = [$field => $value];
            }

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
                NULL,
                json_encode($conversion['parameters']),
                (!empty($conversion['products'])) ? json_encode($conversion['products']) : NULL,
                (!empty($other_parameters)) ? json_encode($other_parameters) : NULL
            );
        }

    }

    public function admitad_conversion() {

        $a_status = ['new' => 'PENDING', 'pending' => 'PENDING', 'approved' => 'APPROVED', 'declined' => 'REJECTED'];

        $this->load->library('admitad_api');

        $date_start = date('d.m.Y', strtotime('2020-09-01'));
        $date_end = date('d.m.Y');

        $limit = 100;
        $offset = 0;

        $this->load->library('admitad_api');
        $this->load->model('report_conversion_model');
        while($offset >= 0) {

            $a_conversion = $this->admitad_api->report(NULL, $date_start, $date_end, 'date', $limit, $offset);

            $site_id = $this->config->item('website');

            if(empty($a_conversion['results'])) break;

            foreach($a_conversion['results'] as $conversion) {

                $conversion_id = $conversion['action_id'];

                $status = isset($a_status[$conversion['status']]) ? $a_status[$conversion['status']] : 'PENDING';

                $confirmation_time = ($conversion['status'] == 'approved') ? $conversion['status_updated'] : NULL;

                $products = json_encode($conversion['positions']);

                $rate = $this->admitad_api->rate($conversion['currency'], 'THB', $conversion['action_date']);

                $reward = $conversion['payment'] * $rate;

                $transaction_amount = $conversion['cart'] * $rate;

                $a_data = [
                    'source' => 'admitad',
                    'uid' => $conversion['subid4'],
                    'site_id' => $site_id,
                    'site_name' => $conversion['website_name'],
                    'campaign_id' => $conversion['advcampaign_id'],
                    'campaign_name' => $conversion['advcampaign_name'],
                    'verification_id' => $conversion['order_id'],
                    'click_time' => $conversion['click_date'],
                    'conversion_time' => $conversion['action_date'],
                    'confirmation_time' => $confirmation_time,
                    'status' => $status,
                    'reward' => $reward,
                    'transaction_amount' => $transaction_amount,
                    'products' => $products
                ];

                $this->report_conversion_model->update($conversion_id, $a_data);

            }

            $offset += 1;
    
        }

    }

}
