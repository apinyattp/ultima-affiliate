<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('accesstrade', $data);
    }

    public function accesstrade() {

        $data = json_encode($_GET);
        $this->load->model('callback_model');
        $this->callback_model->create('accesstrade', $data);

        $conversion = $_GET;

        if(empty($conversion)) return TRUE;

        foreach($conversion as $key => $value) {
            $conversion[$key] = ($value == 'empty') ? NULL : $value;
        }

        $a_status = [0 => 'PENDING', 1 => 'APPROVED', 2 => 'REJECTED'];

        $conversion_id = $conversion['conversion_id'];
        $source = 'accesstrade';
        $uid = $conversion['uid'];
        $site_id = $conversion['site_id'];
        $site_name = $conversion['site_name'];
        $campaign_id = $conversion['campaign_id'];
        $campaign_name = $conversion['campaign_name'];
        $customerType = $conversion['customer_type'];
        $creative_id = $conversion['creative_id'];
        $creative_name = NULL;
        $verification_id = $conversion['transaction_id'];
        $click_time = $conversion['click_date'];
        $conversion_time = $conversion['conversion_time'];
        $confirmation_time  = $conversion['confirmation_time'];
        $status = $a_status[$conversion['conversion_status']];
        $reward = $conversion['reward'];
        $transaction_amount = $conversion['total_price'];
        $session_id = NULL;
        $user_agent = $conversion['click_user_agent'];

        $parameters = $products = $other_parameters = NULL;

        $this->load->model('report_conversion_model');
        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);

        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount);

            return TRUE;
        }
    
        $this->report_conversion_model->update_by_conversion_id(
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
            $transaction_amount,
            $session_id,
            $user_agent,
            $parameters,
            $products,
            $other_parameters
        );
    }
    
    public function admitad() {

        $data = json_encode($_GET);
        $this->load->model('callback_model');
        $this->callback_model->create('admitad', $data);

        $conversion = $_GET;

        if(empty($conversion)) return TRUE;

        $a_status = ['new' => 'PENDING', 'pending' => 'PENDING', 'approved' => 'APPROVED', 'declined' => 'REJECTED'];

        $conversion_id = $conversion['admitad_id'];
        $source = 'admitad';
        $uid = $conversion['subId4'];
        $site_id = $conversion['website_id'];
        $site_name = $conversion['website_name'];
        $campaign_id = $conversion['program_id'];
        $campaign_name = $conversion['program_name'];
        $verification_id = $conversion['order_id'];
        $click_time = date('Y-m-d H:i:s', $conversion['click_time']);
        $conversion_time = date('Y-m-d H:i:s', $conversion['action_time']);
        $confirmation_time = ($conversion['payment_status'] == 'approved') ? date('Y-m-d H:i:s') : NULL;
        $status = $a_status[$conversion['payment_status']];
        
        $this->load->library('admitad_api');
        $rate = $this->admitad_api->rate($conversion['currency'], 'THB', $conversion_time);

        $reward = $conversion['commission'] * $rate;
        $transaction_amount = $conversion['order_amount'] * $rate;

        // UPDATE STATUS
        $this->load->model('report_conversion_model');
        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);
        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount);

            return TRUE;
        }

        $customerType = $creative_id = $creative_name = $session_id = NULL;

        $user_agent = $conversion['user_agent'];

        $parameters = $products = $other_parameters = NULL;

        $this->load->model('report_conversion_model');
        $this->report_conversion_model->update_by_conversion_id(
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
            $transaction_amount,
            $session_id,
            $user_agent,
            $parameters,
            $products,
            $other_parameters
        );
    }

    public function involve_asia() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('involve_asia', $data);

        $this->load->model('campaign_model');

        $conversion = $_GET;

        if(empty($conversion)) return TRUE;

        $a_status = ['Pending' => 'PENDING', 'Approved' => 'APPROVED', 'Rejected' => 'REJECTED', 'Paid' => 'PAID', 'Yet to consumed' => 'PENDING', 'Invalid' => 'REJECTED'];

        $conversion_id = $conversion['conversion_id'];
        $source = 'involve_asia';
        $uid = $conversion['uid'];

        $this->load->config('affiliate/involve_asia');
        $site_id = $this->config->item('tracking_link_id');

        $site_name = 'Jelala';
        
        $campaign_code = $this->gen_campaign_code('IVA', $conversion['offer_id']);
        $a_campaign = $this->campaign_model->get_by_code($campaign_code);

        $campaign_id = $a_campaign['id'];
        $campaign_name = $conversion['offer_name'];

        $customerType = $creative_id = $creative_name = NULL;

        $verification_id = $conversion['order_id'];

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['datetime_conversion']));

        $status = isset($a_status[$conversion['status']]) ? $a_status[$conversion['status']] : 'PENDING';

        $confirmation_time = ($status != 'PENDING') ? date('Y-m-d H:i:s') : NULL;
        
        $reward = $conversion['payout_local']; 
        $transaction_amount = $conversion['sale_amount_local'];
        $currency = $conversion['conversion_currency'];
    
        $original_reward = $conversion['usd_payout'];
        $original_transaction_amount = $conversion['usd_sale_amount'];

        $this->load->model('report_conversion_model');
        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);
        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency);

            return TRUE;
        }

        $session_id = $user_agent = NULL;

        $parameters = $other_parameters = NULL;

        $products = json_encode([
            'adv_sub1' => $conversion['adv_sub'],
            'adv_sub2' => $conversion['adv_sub2'],
            'adv_sub3' => $conversion['adv_sub3'],
            'adv_sub4' => $conversion['adv_sub4'],
            'adv_sub5' => $conversion['adv_sub5'],
        ]);

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
    }
    
    public function nsq() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('nsq', $data);
    }

}
