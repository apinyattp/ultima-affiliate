<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function import() {
        // $this->accesstrade_conversion();
        // $this->admitad_conversion();
        $this->involve_asia_conversion();
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
            $click_user_agent = NULL;
            foreach($conversion['parameters'] as $parameter) {
                if($parameter['name'] == 'uid') {
                    $uid = $parameter['value'];
                }else if($parameter['name'] == 'click_user_agent') {
                    $click_user_agent = $parameter['value'];
                }else {
                    continue;
                }
            }

            $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion['conversionId'], 'accesstrade');
            if($conversion['status'] != 'PENDING' && !empty($a_conversion)) {
                $this->report_conversion_model->update_status(
                    $a_conversion['id'], 
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
                $click_user_agent,
                json_encode($conversion['parameters']),
                (!empty($conversion['products'])) ? json_encode($conversion['products']) : NULL,
                (!empty($other_parameters)) ? json_encode($other_parameters) : NULL
            );
        }

    }

    public function admitad_conversion() {

        $a_status = ['new' => 'PENDING', 'pending' => 'PENDING', 'approved' => 'APPROVED', 'declined' => 'REJECTED'];

        $this->load->library('admitad_api');

        $date_start = date('d.m.Y');
        $date_end = date('d.m.Y', strtotime("+1 days"));

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

                $this->report_conversion_model->update($conversion_id, 'admitad', $a_data);

            }

            $offset += 1;
    
        }

    }

    public function involve_asia_conversion() {

        $a_status = ['Pending' => 'PENDING', 'Approved' => 'APPROVED', 'Rejected' => 'REJECTED', 'Paid' => 'PAID', 'Yet to consumed' => 'PENDING', 'Invalid' => 'REJECTED'];

        $this->load->library('involve_asia_api');
        $this->load->model('report_conversion_model');
        $this->load->model('campaign_model');

        $limit = 100;
        $page = 1;
        $source = 'involve_asia';

        $this->load->config('affiliate/involve_asia');
        $site_id = $this->config->item('tracking_link_id');


        $start_date = date('Y-m-d', strtotime('2020-11-01'));
        $end_date = date('Y-m-d');

        while($page >= 1) {
            $a_conversion = $this->involve_asia_api->conversion($start_date, $end_date, NULL, $page, $limit);
            if(empty($a_conversion['data']['data'])) break;

            foreach($a_conversion['data']['data'] as $conversion) {

                $conversion_id = $conversion['conversion_id'];
                $uid = (empty($conversion['aff_sub1'])) ? 0 : $conversion['aff_sub1'];

                $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);
                echo  $this->db->last_query();
                $site_name = 'Jelala';
        
                $campaign_code = $this->gen_campaign_code('IVA', $conversion['offer_id']);
                $a_campaign = $this->campaign_model->get_by_code($campaign_code);
                print_r( $a_conversion);
                print_r( $campaign_code);
                print_r($a_campaign);die();
                $campaign_id = $a_campaign['id'];
                $campaign_name = $conversion['offer_name'];

                $customerType = $creative_id = $creative_name = NULL;

                $verification_id = $conversion['adv_sub1'];

                $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['datetime_conversion']));

                $status = isset($a_status[$conversion['conversion_status']]) ? $a_status[$conversion['conversion_status']] : 'PENDING';

                $confirmation_time = ($status != 'PENDING') ? date('Y-m-d H:i:s') : NULL;
            
                $reward = $conversion['payout'];
                $transaction_amount = $conversion['sale_amount'];
                $original_reward = NULL;
                $original_transaction_amount = NULL;
                $currency = $conversion['currency'];


                $session_id = $user_agent = NULL;

                $parameters = $other_parameters = NULL;

                $products = json_encode([
                    'adv_sub1' => $conversion['adv_sub1'],
                    'adv_sub2' => $conversion['adv_sub2'],
                    'adv_sub3' => $conversion['adv_sub3'],
                    'adv_sub4' => $conversion['adv_sub4'],
                    'adv_sub5' => $conversion['adv_sub5'],
                ]);

                if(empty($a_conversion)) {
                    $this->report_conversion_model->update_by_conversion_id2(
                        $conversion_id,
                        $source,
                        $uid,
                        $site_id,
                        $site_name,
                        $campaign_id,
                        $campaign_name,
                        $customerType,
                        $creative_id,
                        $creative_name,
                        $verification_id,
                        $click_time,
                        $conversion_time,
                        $confirmation_time,
                        $status,
                        $reward,
                        $original_reward,
                        $transaction_amount,
                        $original_transaction_amount,
                        $currency,
                        $session_id,
                        $user_agent,
                        $parameters,
                        $products,
                        $other_parameters
                    );
                }else {

                    if($status != 'PENDING') {
                        $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency);
                    }
            
                }

            }

            $page += 1;
        }
    }
}
