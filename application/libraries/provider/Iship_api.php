<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Iship_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/iship');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_api_key = $this->_ci->config->item('api_key');
        $this->_main_id = $this->_ci->config->item('main_id');
        $this->_campaign_id = $this->_ci->config->item('campaign_id');
    }

    public function conversion($start_time, $end_time) {
        $header = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->_api_key,
        ];

        $url = $this->_endpoint . 'partner_query_orders';
        $params = [
            'start_date' => $start_time,
            'end_date' => $end_time
        ];
        $result = $this->_ci->gateway->curl_get($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function process($conversion) {
        $source = 'iship';
        $a_status = [
            1 => NULL,
            2 => 'PENDING',
            3 => 'APPROVED',
            6 => 'PENDING',
            9 => 'APPROVED',
            10 => 'APPROVED',
            12 => 'APPROVED',
            13 => 'PENDING',
            5 => 'REJECTED',
        ];

        $status = !empty($a_status[$conversion['status']]) ? $a_status[$conversion['status']] : NULL;
        $reward = $conversion['reward'];

        $this->_ci->load->model('report_shipping_model');
        $conversion_id = $this->_ci->report_shipping_model->save($source, $conversion['courier_code'], $conversion['tracking']);

        if (empty($reward) || $status === NULL) return;

        $verification_id = $conversion['tracking'];
        $uid = $conversion['ref_id'];
        if (empty($uid)) $uid = '';

        $this->_ci->load->model('report_conversion_model');
        $this->_ci->load->model('logs_missing_model');
        $this->_ci->load->model('campaign_model');
        $this->_ci->load->model('user_model');

        $a_conversion = $this->_ci->report_conversion_model->get_by_conversion_id($conversion_id, $source);

        $check_missing_conversion = $this->_ci->report_conversion_model->get_by_order_id_with_missing_conversion($verification_id, $uid);
        if(!empty($check_missing_conversion)) {
            $this->_ci->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->_ci->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $site_id = $this->_main_id;
        $site_name = 'Jelala';

        $campaign_id = $this->_campaign_id;
        $a_campaign = $this->_ci->campaign_model->get_by_id($campaign_id);

        $campaign_name = $a_campaign['display_name'];

        $customerType = $creative_id = $creative_name = NULL;

        $click_time = $conversion_time = date('Y-m-d H:i:s', $conversion['timestamp']);

        $confirmation_time = ($status != 'PENDING') ? date('Y-m-d H:i:s') : NULL;


        $transaction_amount = $conversion['price'];
        $original_reward = NULL;
        $original_transaction_amount = NULL;
        $currency = 'th';


        $session_id = $user_agent = NULL;

        $parameters =  NULL;

        $products = [];
        $other_parameters = '';
        $remark = '';

        if(empty($a_conversion)) {
            $company = NULL;
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
                $other_parameters,
                $remark
            );
        }else {
            if($status != 'PENDING') {
                $this->_ci->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency);
            }
        }
    }

}
