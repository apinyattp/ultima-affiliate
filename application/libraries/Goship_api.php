<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Goship_api {

    private $_ci;

    private $_endpoint;
    private $_api_secret;
    private $_api_key;

    public function __construct () {
        $this->_ci = &get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/goship');

        $this->_endpoint = $this->_ci->config->item('endpoint');
        $this->_api_key = $this->_ci->config->item('api_key');
        $this->_main_id = $this->_ci->config->item('main_id');
        $this->_carrier = $this->_ci->config->item('carrier');
        $this->_campaign_id = $this->_ci->config->item('campaign_id');
    }

    public function conversion($start_date, $end_date, $page=1, $limit=10) {

        $header = [
            'Accept: application/json',
            'Authorization: Bearer ' . $this->_api_key,
            'User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)',
        ];

        $url = $this->_endpoint . 'whitelabel/getshipment';
        $params = [
            'page' => $page,
            'per_page' => $limit,
            'date_start' => $start_date,
            'date_end' => $end_date
        ];
        $result = $this->_ci->gateway->curl_get($url, $params, $header);
        return json_decode($result, TRUE);
    }

    public function _goship_process($conversion) {
        if ($conversion['status'] == 'waiting') return;

        $source = 'goship';
        $a_status = [
            'on_delivery' => 'PENDING',
            'on_return' => 'PENDING',
            'returned' => 'APPROVED',
            'claimed' => 'APPROVED',
            'delivered' => 'APPROVED',
            'canceled' => 'REJECTED'
        ];

        $verification_id = $conversion['tracking_number'];
        $uid = $conversion['uuid'];

        $this->_ci->load->model('report_conversion_model');
        $this->_ci->load->model('logs_missing_model');
        $this->_ci->load->model('campaign_model');

        $a_conversion = $this->_ci->report_conversion_model->get_by_verification_id($verification_id);
        $conversion_id = -1;

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

        $verification_id = $conversion['tracking_number'];

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['shipment_date']));

        $status = !empty($a_status[$conversion['status']]) ? $a_status[$conversion['status']] : 'PENDING';

        $confirmation_time = ($status != 'PENDING') ? date('Y-m-d H:i:s') : NULL;

        $price_min = !empty($this->_carrier[$conversion['carrier']]['price_min']) ? $this->_carrier[$conversion['carrier']]['price_min'] : NULL;
        $percent = !empty($this->_carrier[$conversion['carrier']]['percent']) ? $this->_carrier[$conversion['carrier']]['percent'] : 0;
        $percent_cod = !empty($this->_carrier[$conversion['carrier']]['percent_cod']) ? $this->_carrier[$conversion['carrier']]['percent_cod'] : 0;

        if (empty($percent) || empty($percent_cod)) return;

        $cod_price = empty($conversion['cod_price']) ? 0 : (int)$conversion['cod_price'];
        $actual_price = empty($conversion['actual_price']) ? 0 : (int)$conversion['actual_price'];

        $reward_actual = (float)($actual_price * ($percent/100));
        $reward_cod = (float)($cod_price * ($percent_cod/100));

        if ($reward_actual <= $price_min) {
            $reward_actual = 0;
        }

        $reward = $reward_actual + $reward_cod;

        $transaction_amount = $actual_price + $cod_price;
        $original_reward = NULL;
        $original_transaction_amount = NULL;
        $currency = 'th';


        $session_id = $user_agent = NULL;

        $parameters =  NULL;

        $products = [];
        $other_parameters = json_encode($conversion);

        if(empty($a_conversion)) {
            $company = 'shopgenix';
            $this->_ci->report_conversion_model->update_by_verification_id(
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
