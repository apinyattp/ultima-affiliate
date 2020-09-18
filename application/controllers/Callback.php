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
    }
    
    public function admitad() {

        $conversion = $_GET;

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
        $confirmation_time = NULL;
        $status = $a_status[$conversion['payment_status']];
        
        $this->load->model('data_model');
        $exchange_rate = $this->data_model->get_by_date($source, date('Y-m-d', $conversion['action_time']));
        if(empty($exchange_rate)) {
            $this->load->library('admitad_api');
            $exchange_rate = $this->admitad_api->currency_exchange_rate($conversion['currency'], 'THB', $conversion['action_time']);

            $this->data_model->create('admitad', $exchange_rate['base'], $exchange_rate['target'], $exchange_rate['rate'], $exchange_rate['date']);
        }

        $rate = $exchange_rate['rate'];

        $reward = $conversion['commission'] * $rate;
        $transaction_amount = $conversion['order_amount'] * $rate;

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

        $data = json_encode($_GET);

        $this->load->model('callback_model');
        $this->callback_model->create('admitad', $data);
    }

}
