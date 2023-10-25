<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->db->save_queries = FALSE;
    }

    public function import() {
        $this->accesstrade_conversion();
        $this->iship_conversion();
        $this->involve_asia_conversion_pending();
        echo 'success date time: ' .  date('d/m/Y h:i:s a', time());
    }

    public function import_midnight() {
        for($i=0;$i<30;$i++){
            $this->tqm_conversion(date('Y-m-d', strtotime("-$i days")));
        }
        for($i=0;$i<2;$i++){
            $this->tqm_conversion_reject(date('Y-m-d', strtotime("-$i months")));
        }
        $this->involve_asia_conversion();

        echo 'success date time: ' .  date('d/m/Y h:i:s a', time());
    }

    public function accesstrade_conversion($fromDate=NULL, $toDate=NULL) {
        if(!$fromDate) $fromDate = date('Y-m-d');
        if(!$toDate) $toDate = date('Y-m-d', strtotime("+1 days"));

        $this->load->library('provider/accesstrade');
        $conversions = $this->accesstrade->conversion($fromDate, $toDate);

        if(empty($conversions)) return TRUE;

        $totalReward = $conversions['totalReward'];
        $totalConversionsCount = $conversions['totalConversionsCount'];

        $this->load->model('report_conversion_model');
        $this->load->model('logs_missing_model');
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

            $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['verificationId'], $uid);
            if(!empty($check_missing_conversion)) {
                $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
                $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
            }

            $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion['conversionId'], 'accesstrade');
            if($conversion['status'] != 'PENDING' && !empty($a_conversion)) {
                if ($a_conversion['status'] === 'PAID') continue;

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

            $company = NULL;
            $this->load->model('user_model');
            $user = $this->user_model->get_by_jelala_id($uid);
            if($user) {
                $company = $user['company'];
            }

            $this->report_conversion_model->update_by_conversion_id(
                $conversion['conversionId'],
                'accesstrade',
                $uid,
                $company,
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

        $this->load->library('provider/admitad_api');

        $date_start = date('d.m.Y');
        $date_end = date('d.m.Y', strtotime("+1 days"));

        $limit = 100;
        $offset = 0;

        $this->load->library('provider/admitad_api');
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

                $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['order_id'], $conversion['subid4']);
                if(!empty($check_missing_conversion)) {
                    $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
                    $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
                }

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
        $this->load->library('provider/involve_asia_api');
        $this->load->model('report_conversion_model');

        $start = time();

        // $page = 1;
        // $last_id = 0;
        // do {
        //     $a_data = $this->report_conversion_model->get_conversion_id_by_last_id($last_id, ['APPROVED', 'PENDING'], 'involve_asia', 90);
        //     if(empty($a_data)) break;

        //     $last_id = $a_data[count($a_data)-1]['id'];
        //     $a_conversion_id = array_column($a_data, 'conversion_id');

        //     for ($retry=0; $retry <= 10; $retry++) {
        //         $result = $this->involve_asia_api->conversion_by_id($a_conversion_id);

        //         if(!empty($result['status_code'])) {
        //             switch($result['status_code']) {
        //                 case 429:
        //                     sleep(20);
        //                     continue 2;
        //             }
        //         }

        //         break;
        //     }


        //     if(!empty($result['data']['data'])) {
        //         foreach($result['data']['data'] as $conversion) {
        //             $this->_involve_asia_conversion_process($conversion);
        //         }
        //     }

        //     $time = time() - $start;
        //     echo "PAGE $page : $last_id : $time\n";

        //     sleep(4);
        //     $page += 1;
        // }while(!empty($a_data));

        $this->involve_asia_conversion_pending(210);
    }

    public function involve_asia_conversion_pending($days=7) {
        $this->load->library('provider/involve_asia_api');
        $this->load->model('report_conversion_model');

        for ($i=$days; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime(date('Y-m-d')." -$i days"));
            $now = date('Y-m-d H:i:s');
            echo "$date ($now) \n";
            // $this->_involve_asia_conversion($date, $date, ['pending', 'yet to consumed', 'approved']);
            $this->_involve_asia_conversion($date, $date, []);
            sleep(4);
        }
    }

    public function involve_asia_conversion_date($start_date, $end_date=NULL) {
        $this->load->library('provider/involve_asia_api');
        $this->load->model('report_conversion_model');
        if (empty($end_date)) $end_date = $start_date;
        $this->_involve_asia_conversion($start_date, $end_date, []);
    }

    private function _involve_asia_conversion($start_date, $end_date, $a_status) {
        $limit = 1000;
        $page = 1;

        $start = time();
        $conversion_time = '';
        for(;$page <= 10000;) {
            $result = $this->involve_asia_api->conversion($start_date, $end_date, NULL, $a_status, $page, $limit);
            if(empty($result)) {
                $time = time() - $start;
                echo "PAGE $page : $conversion_time : $time -- NULL\n";
                sleep(3);
                continue;
            }

            if(!empty($result['status_code'])) {
                switch($result['status_code']) {
                    case 429:
                        sleep(20);
                        $time = time() - $start;
                        echo "PAGE $page : $conversion_time : $time -- 429 sleep 20\n";
                        continue 2;
                    default:
                        $time = time() - $start;
                        echo "PAGE $page : $conversion_time : $time -- ".$result['status_code']."\n";
                }
            }

            if(empty($result['data']['data'])) break;

            foreach($result['data']['data'] as $conversion) {
                $this->_involve_asia_conversion_process($conversion);
                $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['datetime_conversion']));
            }
            $time = time() - $start;

            echo "PAGE $page : $conversion_time : $time\n";

            sleep(3);
            $page += 1;
        }
    }

    private function _involve_asia_conversion_process($conversion) {
        $this->load->model('logs_missing_model');
        $this->load->model('campaign_model');
        $this->load->config('affiliate/involve_asia');

        $source = 'involve_asia';
        $site_id = $this->config->item('tracking_link_id');
        $a_status = ['Pending' => 'PENDING', 'Approved' => 'APPROVED', 'Rejected' => 'REJECTED', 'Paid' => 'PAID', 'Yet to consumed' => 'PENDING', 'Invalid' => 'INVALID'];

        $conversion_id = $conversion['conversion_id'];
        $uid = (empty($conversion['aff_sub1'])) ? 0 : $conversion['aff_sub1'];

        $check_missing_conversion = $this->report_conversion_model->get_by_order_id_with_missing_conversion($conversion['adv_sub1'], $uid);
        if(!empty($check_missing_conversion)) {
            $this->report_conversion_model->delete_conversion($check_missing_conversion['id']);
            $this->logs_missing_model->insert_logs($check_missing_conversion['id']);
        }

        $a_conversion = $this->report_conversion_model->get_by_conversion_id($conversion_id, $source);
        $site_name = 'Jelala';

        $campaign_code = $this->gen_campaign_code('IVA', $conversion['offer_id']);
        $a_campaign = $this->campaign_model->get_by_code($campaign_code);

        $campaign_id = isset($a_campaign['id']) ? $a_campaign['id'] : 0;
        $campaign_name = $conversion['offer_name'];

        if(is_null($campaign_id)) {
            $campaign_id = 0;
        }
        $customerType = $creative_id = $creative_name = NULL;

        $verification_id = $conversion['adv_sub1'];

        $click_time = $conversion_time = date('Y-m-d H:i:s', strtotime($conversion['datetime_conversion']));

        $status = isset($a_status[ucfirst($conversion['conversion_status'])]) ? $a_status[ucfirst($conversion['conversion_status'])] : 'PENDING';

        if ($status == 'PENDING') {
            $confirmation_time = NULL;
        }elseif ($status == 'PAID') {
            $confirmation_time = empty($a_conversion['paid_time']) ? date('Y-m-d H:i:s') : $a_conversion['paid_time'];
        }else{
            $confirmation_time = empty($a_conversion['confirmation_time']) ? date('Y-m-d H:i:s') : $a_conversion['confirmation_time'];
        }

        $reward = $original_reward = $conversion['payout'];
        $transaction_amount = $original_transaction_amount = $conversion['sale_amount'];
        $currency = $conversion['currency'];
        $remark = $conversion['affiliate_remarks'];

        // missing conversion
        if (empty($conversion['adv_sub2'])
            && empty($conversion['adv_sub3'])
            && empty($conversion['adv_sub4'])
            && empty($conversion['adv_sub5'])) {
                if(in_array($status, ['PENDING'])){
                    $reward = 0;
                }elseif(!empty($a_conversion) && in_array($status, ['REJECTED', 'INVALID'])){
                    $reward = $a_conversion['reward'];
                }
            }

        switch ($currency) {
            case 'USD':
                $reward = $reward * 30;
                $transaction_amount = $transaction_amount * 30;
                break;
        }

        $session_id = $user_agent = NULL;

        $parameters = $other_parameters = NULL;

        $products = json_encode([
            'adv_sub1' => $conversion['adv_sub1'],
            'adv_sub2' => $conversion['adv_sub2'],
            'adv_sub3' => $conversion['adv_sub3'],
            'adv_sub4' => $conversion['adv_sub4'],
            'adv_sub5' => $conversion['adv_sub5'],
            'payout_local' => $original_reward,
        ]);

        if(empty($a_conversion)) {
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
                $other_parameters,
                $remark
            );
        }else {
            if($status != 'PENDING') {
                $this->report_conversion_model->update_status($a_conversion['id'], $status, $confirmation_time, $reward, $transaction_amount, $original_reward, $original_transaction_amount, $currency, $remark);
            }
        }
    }

    public function safari_conversion() {
        $this->load->library('provider/safari_api');
        $this->load->model('report_conversion_model');
        $this->load->model('campaign_model');
        $this->load->model('logs_missing_model');

        $start_date = date('Y-m-d', strtotime('-1 day'));
        $end_date = date('Y-m-d', time());
        $a_conversion = $this->safari_api->conversion($start_date, $end_date);

        if(!empty($a_conversion['order'])) {
            foreach($a_conversion['order'] as $conversion) {
                $this->safari_api->_safari_process($conversion);
            }
        }
    }

    public function goship_conversion() {
        $this->load->library('provider/goship_api');
        $this->load->model('report_conversion_model');
        $this->load->model('campaign_model');
        $this->load->model('logs_missing_model');

        $end_date = date('Y-m-d', strtotime('-1 day'));
        $start_date = date('Y-m-d', time());

        $a_conversion = $this->goship_api->conversion($start_date, $end_date);

        $last_page = $a_conversion['last_page'];

        for ($page = 1; $page <= $last_page; $page++) {
            if ($page > 1) {
                $a_conversion = $this->goship_api->conversion($start_date, $end_date, $page);
            }

            if(!empty($a_conversion['data'])) {
                foreach($a_conversion['data'] as $conversion) {
                    $this->goship_api->_goship_process($conversion);
                }
            }
        }
    }

    public function iship_conversion($start_date=NULL, $days=1) {
        $this->load->library('provider/iship_api');
        $this->load->model('report_conversion_model');

        if ($start_date) {
            $start_date = strtotime($start_date);
        } else {
            $start_date = strtotime('-1 day', time());
        }

        for($day=1; $day <= $days; $day++) {
            $end_date = strtotime('+1 day', $start_date);

            $result = $this->iship_api->conversion($start_date, $end_date);

            if(!empty($result['data'])) {
                foreach($result['data'] as $conversion) {
                    $this->iship_api->process($conversion);
                }
            }

            $start_date = $end_date;
        }

    }

    public function tqm_conversion($date=NULL) {
        $this->load->library('provider/tqm_api');
        $this->load->model('report_conversion_model');

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $result = $this->tqm_api->conversion($date);
        if($result['countResult'] > 0 && !empty($result['result']) && is_array($result['result'])) {
            foreach($result['result'] as $conversion) {
                $this->tqm_api->process($conversion);
            }
        }
    }
    public function tqm_conversion_reject($date=NULL) {
        $this->load->library('provider/tqm_api');
        $this->load->model('report_conversion_model');

        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $date_m = date('Y-m', strtotime($date));
        $result = $this->tqm_api->conversion_reject($date_m);
        if($result['countResult'] > 0 && !empty($result['result']) && is_array($result['result'])) {
            foreach($result['result'] as $conversion) {
                $this->tqm_api->process_reject($conversion);
            }
        }
    }
}
