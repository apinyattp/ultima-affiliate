<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tqm_api {

    private $_ci;

    private $_endpoint;
    private $_api_username;
    private $_api_password;
    private $_main_id;
    private $_campaign_id;
    private $_company;

    private $_token;
    private $_token_time = 0;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/tqm');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_api_username = $this->_ci->config->item('api_username');
        $this->_api_password = $this->_ci->config->item('api_password');
        $this->_main_id = $this->_ci->config->item('main_id');
        $this->_campaign_id = $this->_ci->config->item('campaign_id');
        $this->_company = $this->_ci->config->item('company');
    }

    public function authenticate() {
        $params = [
            'username' => $this->_api_username,
            'password' => $this->_api_password,
        ];

        $url = $this->_endpoint . 'login';

        $header = [
            'Accept: application/json'
        ];

        $result = $this->_ci->gateway->curl_post($url, $params, $header);

        return json_decode($result, TRUE);
    }

    public function _auth() {
        if($this->_token && $this->_token_time < time()) {
            return $this->_token;
        }

        $result = $this->authenticate();

        $this->_token = $result['apiToken'];
        $this->_token_time = time() + $result['expiredIn'];

        return $this->_token;
    }

    private $_campaign;
    public function _campaign() {
        if($this->_campaign) return $this->_campaign;
        $this->_ci->load->model('campaign_model');
        $this->_campaign = $this->_ci->campaign_model->get_by_id($this->_campaign_id);
        return $this->_campaign;
    }

    public function conversion($date) {
        $header = [
            'Accept: application/json',
            'Authorization: ' . $this->_auth(),
        ];

        $url = $this->_endpoint . 'affiliate/sale-daily-report';
        $params = [
            'reportDate' => $date
        ];
        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function conversion_reject($date) {
        $header = [
            'Accept: application/json',
            'Authorization: ' . $this->_auth(),
        ];

        $url = $this->_endpoint . 'affiliate/sale-cancellation-report';
        $params = [
            'cancelDate' => $date
        ];
        $result = $this->_ci->gateway->curl_post($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function process($conversion, $status='APPROVED') {
        $source = 'tqm';

        $reward = $conversion['commission'];

        if (empty($reward) || $status === NULL) return;

        $verification_id = $conversion['saleId'];
        $uid = $conversion['checkSum'];
        if (empty($uid)) $uid = $conversion['shopGenixId'];
        if (empty($uid)) $uid = '';

        $this->_ci->load->model('report_conversion_model');
        $this->_ci->load->model('report_shipping_model');
        $this->_ci->load->model('logs_missing_model');
        $this->_ci->load->model('user_model');

        $a_conversion = $this->_ci->report_conversion_model->get_by_verification_id($verification_id, $source);
        if ($a_conversion['status'] === 'REJECTED') return;

        $site_id = $this->_main_id;
        $site_name = 'Jelala';

        $campaign_id = $this->_campaign_id;
        $a_campaign = $this->_campaign();

        $campaign_name = $a_campaign['display_name'];

        $customerType = $creative_id = $creative_name = NULL;

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['createdDate']));

        if ($status == 'PENDING') {
            $confirmation_time = NULL;
        }else{
            $confirmation_time = empty($a_conversion['confirmation_time']) ? date('Y-m-d H:i:s') : $a_conversion['confirmation_time'];
        }

        $transaction_amount = $conversion['amount'];
        $original_reward = NULL;
        $original_transaction_amount = NULL;
        $currency = 'THB';


        $session_id = $user_agent = NULL;

        $parameters =  NULL;

        $products = [];
        $other_parameters = '';
        $remark = '';

        if(empty($a_conversion)) {
            $company = $this->_company;
            $user = $this->_ci->user_model->get_by_jelala_id($uid);
            if($user) {
                $company = $user['company'];
            }

            $this->_ci->report_conversion_model->update_by_verification_id(
                -1,
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

    public function process_reject($conversion) {
        $source = 'tqm';

        $reward = $conversion['commissionRefund'];

        $a_status = [
            'PENDING' => 'PENDING',
            'COMPLETE' => 'APPROVED',
        ];
        $status = !empty($a_status[$conversion['status']]) ? $a_status[$conversion['status']] : NULL;
        if ($status === NULL) return;

        $verification_id = $conversion['saleId'];
        $a_conversion = $this->_ci->report_conversion_model->get_by_verification_id($verification_id, $source);
        if(empty($a_conversion)) return;

        if ($reward <= 0 && $status == 'APPROVED') {
            $_reward = $a_conversion['reward'];
            $transaction_amount = $a_conversion['transaction_amount'];
            $original_reward = $a_conversion['original_reward'];
            $original_transaction_amount = $a_conversion['original_transaction_amount'];
            $currency = $a_conversion['currency'];

            $confirmation_time = $a_conversion['status'] != 'REJECTED' ? date('Y-m-d H:i:s') : $a_conversion['confirmation_time'];

            $this->_ci->report_conversion_model->update_status($a_conversion['id'], 'REJECTED', $confirmation_time, $_reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency);
            return;
        }

        if (empty($reward) || $status != 'APPROVED') return;

        $verification_id = '-'.$conversion['saleId'];
        $uid = $conversion['checkSum'];
        if (empty($uid)) $uid = $conversion['shopGenixId'];
        if (empty($uid)) $uid = '';

        $this->_ci->load->model('report_conversion_model');
        $this->_ci->load->model('report_shipping_model');
        $this->_ci->load->model('logs_missing_model');
        $this->_ci->load->model('user_model');

        $a_conversion = $this->_ci->report_conversion_model->get_by_verification_id($verification_id, $source);

        $site_id = $this->_main_id;
        $site_name = 'Jelala';

        $campaign_id = $this->_campaign_id;
        $a_campaign = $this->_campaign();

        $campaign_name = $a_campaign['display_name'];

        $customerType = $creative_id = $creative_name = NULL;

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['cancelDate']));

        $confirmation_time = empty($a_conversion['confirmation_time']) ? date('Y-m-d H:i:s') : $a_conversion['confirmation_time'];


        $transaction_amount = $conversion['amount'];
        $reward = -$reward;
        $original_reward = NULL;
        $original_transaction_amount = NULL;
        $currency = 'th';


        $session_id = $user_agent = NULL;

        $parameters =  NULL;

        $products = [];
        $other_parameters = '';
        $remark = '';

        if(empty($a_conversion)) {
            $company = $this->_company;
            $user = $this->_ci->user_model->get_by_jelala_id($uid);
            if($user) {
                $company = $user['company'];
            }

            $this->_ci->report_conversion_model->update_by_verification_id(
                -1,
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
