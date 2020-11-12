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
        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id);

        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($conversion_id, $status, $confirmation_time, $reward, $transaction_amount);

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
        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id);
        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($conversion_id, $status, $confirmation_time, $reward, $transaction_amount);

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
    }
    
    public function nsq() {
        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('nsq', $data);
    }

}
