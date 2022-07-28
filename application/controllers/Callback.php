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
        $user_agent = $conversion['user_agent'];

        $parameters = $products = $other_parameters = NULL;

        $this->load->model('report_conversion_model');
        $this->load->model('logs_missing_model');

        $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['conversion_id'], $uid);
        if(!empty($check_missing_conversion)) {
            $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);

        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount);

            return TRUE;
        }

        $company = NULL;
        $this->load->model('user_model');
        $user = $this->user_model->get_by_jelala_id($uid);
        if($user) {
            $company = $user['company'];
        }

        $this->report_conversion_model->update_by_conversion_id(
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

        $this->load->library('provider/admitad_api');
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

        $company = NULL;
        $this->load->model('user_model');
        $user = $this->user_model->get_by_jelala_id($uid);
        if($user) {
            $company = $user['company'];
        }

        $this->load->model('report_conversion_model');
        $this->report_conversion_model->update_by_conversion_id(
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
        $this->load->model('report_conversion_model');
        $this->load->model('logs_missing_model');

        $this->callback_model->create('involve_asia', $data);

        $this->load->model('campaign_model');

        $conversion = $_GET;

        if(empty($conversion)) return TRUE;

        $a_status = ['Pending' => 'PENDING', 'Approved' => 'APPROVED', 'Rejected' => 'REJECTED', 'Paid' => 'PAID', 'Yet to consumed' => 'PENDING', 'Invalid' => 'INVALID'];

        $conversion_id = $conversion['conversion_id'];
        $source = 'involve_asia';
        $uid = $conversion['uid'];

        $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['order_id'], $uid);
        if(!empty($check_missing_conversion)) {
            $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $this->load->config('affiliate/involve_asia');
        $site_id = $this->config->item('tracking_link_id');

        $site_name = 'Jelala';

        $campaign_code = $this->gen_campaign_code('IVA', $conversion['offer_id']);
        $a_campaign = $this->campaign_model->get_by_code($campaign_code);
        if(empty($a_campaign)) return;
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

        // missing conversion
        if ($conversion['adv_sub'] == $verification_id
            && empty($conversion['adv_sub2'])
            && empty($conversion['adv_sub3'])
            && empty($conversion['adv_sub4'])
            && empty($conversion['adv_sub5'])) {
                if(in_array($status, ['PENDING'])){
                    $reward = 0;
                }elseif(!emprt($a_conversion) && in_array($status, ['REJECTED', 'INVALID'])){
                    $reward = $a_conversion['reward'];
                }
            }

        switch ($currency) {
            case 'USD':
                $reward = $reward * 30;
                $transaction_amount = $transaction_amount * 30;
                break;
        }

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
            'payout_local' => $conversion['payout_local'],
        ]);

        $company = NULL;
        $this->load->model('user_model');
        $user = $this->user_model->get_by_jelala_id($uid);
        if($user) {
            $company = $user['company'];
        }

        $this->report_conversion_model->update_by_conversion_id2(
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
    }

    public function shopgenix() {
        $source = 'shopgenix';

        $data = $this->input->post();

        $this->load->model('callback_model');
        $this->callback_model->create('shopgenix', json_encode($data));

        $conversion = $data;

        if(empty($conversion)) return TRUE;

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $conversion_id = $conversion['id'];
        $uid = $conversion['affiliate_uuid'];
        $site_id = '0';
        $site_name = 'shopgenix';
        $campaign_id = $conversion['store_id'];
        $campaign_name = $conversion['store_name'];
        $verification_id = $conversion['no'];
        $click_time = $conversion['datetime_conversion'];
        $conversion_time = $conversion['datetime_conversion'];
        $confirmation_time  = $conversion['datetime_conversion'];
        $status = $a_status[$conversion['status']];
        $reward = $conversion['reward'];
        $transaction_amount = $conversion['price'];

        $session_id = $user_agent = NULL;
        $parameters = $products = $other_parameters = NULL;
        $customerType = $creative_id = $creative_name = $session_id = NULL;

        $this->load->model('report_conversion_model');
        $this->load->model('logs_missing_model');

        $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['no'], $uid);
        if(!empty($check_missing_conversion)) {
            $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);

        if($status != 'PENDING' && !empty($a_conversion)) {
            $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount);

            return TRUE;
        }

        $company = 'shopgenix';

        $this->report_conversion_model->update_by_conversion_id(
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
            $transaction_amount,
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

    public function cfmanager() {
        $conversion = $this->input->post();

        if(empty($conversion)) return $this->_echo_json(E::INVALID_FORMAT, ['error' => 'no data']);

        $this->load->model('callback_model');

        $this->load->library('provider/safari_api');

        if(!empty($conversion)) {
            $this->safari_api->_safari_process($conversion);
        }

        $this->callback_model->create('cfmanager', json_encode($conversion));

        $this->_echo_json(E::SUCCESS);
    }

    public function goship() {
        $conversion = $this->input->post();

        if(empty($conversion)) return $this->_echo_json(E::INVALID_FORMAT, ['error' => 'no data']);

        $this->load->model('callback_model');

        $this->callback_model->create('goship', json_encode($conversion));

        $this->_echo_json(E::SUCCESS);
    }

    public function iship() {
        $source = 'iship';
        if(($auth = $this->_authorization_src([$source])) !== TRUE) return $this->_echo_json($auth);

        $conversion = $this->input->post();

        if(empty($conversion)) return $this->_echo_json(E::INVALID_FORMAT, ['error' => 'no data']);

        $this->load->model('callback_model');
        $this->callback_model->create('iship', json_encode($conversion));

        $a_validator = [
            'ref_code' => 'required|string',
            'courier_code' => 'required|string',
            'tracking' => 'required|string',
            'status' => 'required|enum(1;2;3;6;9;10;12)',
            'timestamp' => 'required|int',
            'price' => 'required|float',
            'reward' => 'required|float',
            'ref_id' => 'required|string',
        ];

        foreach($a_validator as $var_name => $validate) {
            $input = new \Builder\Input\Input($var_name, $validate);

            if(($result = $input->validate()) !== TRUE) {
                return $this->_echo_json($result->error_code, $result->data);
            }
        }

        $this->load->library('provider/iship_api');
        $this->iship_api->process($conversion);

        $this->_echo_json(E::SUCCESS);
    }

}
