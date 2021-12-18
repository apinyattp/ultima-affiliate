<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Safari_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    private $_token;
    private $_token_time = 0;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/safari');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_main_id = $this->_ci->config->item('main_id');
        $this->_api_secret = $this->_ci->config->item('api_secret');
        $this->_api_key = $this->_ci->config->item('api_key');
    }

    public function conversion($start_date, $end_date) {

        $header = [
            'Accept: application/json',
            'apikey: ' . $this->_api_key,
            'apisecret: ' . $this->_api_secret,
        ];

        $url = $this->_endpoint . 'get_order_main';

        $params = [
            'main_id' => $this->_main_id,
            'start_date' => $start_date,
            'end_date' => $end_date
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function _safari_process($conversion) {
        $source = 'cfmanager';
        $a_status = ['0' => 'PENDING', '1' => 'PENDING', '2' => 'APPROVED', '9' => 'REJECTED'];

        $conversion_id = $conversion['order_id'];
        $uid = $conversion['ref_code2'];

        if(empty($uid)) return;
        
        $this->_ci->load->model('report_conversion_model');
        $this->_ci->load->model('logs_missing_model');
        $this->_ci->load->model('campaign_model');

        $check_missing_conversion = $this->_ci->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['order_id'], $uid);
        if(!empty($check_missing_conversion)) {
            $this->_ci->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->_ci->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $a_conversion = $this->_ci->report_conversion_model->get_by_conversion_id($conversion_id, $source);

        $site_id = $conversion['main_agent_id'];
        $site_name = 'Jelala';

        // $campaign_id = empty($conversion['ref_code']) ? '21098': $conversion['ref_code'];
        $campaign_id = '21098';
        $a_campaign = $this->_ci->campaign_model->get_by_id($campaign_id);
        $campaign_name = $a_campaign['display_name'];

        $customerType = $creative_id = $creative_name = NULL;

        $verification_id = $conversion['order_id'];

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['created']));

        $status = !empty($a_status[$conversion['order_status']]) ? $a_status[$conversion['order_status']] : 'PENDING';

        $confirmation_time = ($status != 'PENDING') ? date('Y-m-d H:i:s') : NULL;

        $reward = (float)$conversion['cashback'];

        $transaction_amount = $conversion['order_sum'];
        $original_reward = NULL;
        $original_transaction_amount = NULL;
        $currency = 'th';


        $session_id = $user_agent = NULL;

        $parameters =  NULL;

        $products = [];
        $other_parameters = json_encode($conversion);

        if(empty($a_conversion)) {
            $company = NULL;
            $this->_ci->load->model('user_model');
            $user = $this->_ci->user_model->get_by_jelala_id($uid);
            if($user) {
                $company = $user['company'];
            }

            $this->_ci->report_conversion_model->update_by_conversion_id2(
                $conversion_id,
                $source,
                $uid,
                $company,
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
                $this->_ci->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency);
            }
        }
    }

}
