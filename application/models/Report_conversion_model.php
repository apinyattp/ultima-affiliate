<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_conversion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function get_by_conversion_id($conversion_id, $source=FALSE) {
        $this->db->where('conversion_id', $conversion_id);
        if($source) $this->db->where('source', $source);
        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function update_by_conversion_id($conversion_id, $source, $uid, $site_id, $site_name, $campaign_id, $campaign_name, $customerType=NULL, $creative_id, $creative_name, $verification_id, $click_time, $conversion_time, $confirmation_time=NULL, $status, $reward, $transaction_amount, $session_id, $user_agent=NULL, $parameters=NULL, $products=NULL, $other_parameters=NULL, $missing_id = 0) {
        $a_data = [
            'conversion_id' => $conversion_id,
            'uid' => $uid,
            'site_id' => $site_id,
            'site_name' => $site_name,
            'campaign_id' => $campaign_id,
            'campaign_name' => $campaign_name,
            'creative_id' => $creative_id,
            'creative_name' => $creative_name,
            'customerType' => $customerType,
            'verification_id' => $verification_id,
            'click_time' => $click_time,
            'conversion_time' => $conversion_time,
            'confirmation_time' => $confirmation_time,
            'status' => $status,
            'reward' => $reward,
            'transaction_amount' => $transaction_amount,
            'session_id' => $session_id,
            'user_agent' => $user_agent,
            'parameters' => empty($parameters) ? NULL : $parameters,
            'products' => empty($products) ? NULL : $products,
            'other_parameters' => empty($other_parameters) ? NULL : $other_parameters,
            'source' => $source,
            'missing_id' => $missing_id
        ];
        $conversion = $this->get_by_conversion_id($conversion_id);
        if(empty($conversion)) {
            $this->db->insert('report_conversion', $a_data);
        }else {
            $this->db->where('source', $source);
            $this->db->where('conversion_id', $conversion_id);
            $this->db->update('report_conversion', $a_data);
        }
    }

    public function update_by_conversion_id2($conversion_id, $source, $uid, $site_id, $site_name, $campaign_id, $campaign_name, $customerType=NULL, $creative_id, $creative_name, $verification_id, $click_time, $conversion_time, $confirmation_time=NULL, $status, $reward, $original_reward=NULL, $transaction_amount, $original_transaction_amount=NULL, $currency=NULL, $session_id, $user_agent=NULL, $parameters=NULL, $products=NULL, $other_parameters=NULL, $missing_id = 0, $paid_time=FALSE) {
        $a_data = [
            'conversion_id' => $conversion_id,
            'uid' => $uid,
            'site_id' => $site_id,
            'site_name' => $site_name,
            'campaign_id' => $campaign_id,
            'campaign_name' => $campaign_name,
            'creative_id' => $creative_id,
            'creative_name' => $creative_name,
            'customerType' => $customerType,
            'verification_id' => $verification_id,
            'click_time' => $click_time,
            'conversion_time' => $conversion_time,
            'confirmation_time' => $confirmation_time,
            'status' => $status,
            'reward' => $reward,
            'original_reward' => $original_reward,
            'transaction_amount' => $transaction_amount,
            'original_transaction_amount' => $original_transaction_amount,
            'currency' => $currency,
            'session_id' => $session_id,
            'user_agent' => $user_agent,
            'parameters' => empty($parameters) ? NULL : $parameters,
            'products' => empty($products) ? NULL : $products,
            'other_parameters' => empty($other_parameters) ? NULL : $other_parameters,
            'source' => $source,
            'missing_id' => $missing_id
        ];
        if(!empty($paid_time)) {
            $a_data['paid_time'] = date('Y-m-d H:i:s', strtotime($paid_time));
        }
        $conversion = $this->get_by_conversion_id($conversion_id);
        if(empty($conversion)) {
            $this->db->insert('report_conversion', $a_data);
        }else {
            $this->db->where('source', $source);
            $this->db->where('conversion_id', $conversion_id);
            $this->db->update('report_conversion', $a_data);
        }
    }

    public function update($conversion_id, $source, $a_data) {
        $this->db->where('source', $source);
        $this->db->where('conversion_id', $conversion_id);
        $this->db->update('report_conversion', $a_data);
    }

    public function update_status($id, $status, $time, $reward, $transaction_amount, $original_reward=NULL, $original_transaction_amount=NULL, $currency=NULL) {

        $time_column = ($status == 'PAID') ? 'paid_time' : 'confirmation_time';

        $a_data = [
            'status' => $status,
            $time_column => $time,
            'reward' => $reward,
            'original_reward' => $original_reward,
            'transaction_amount' => $transaction_amount,
            'original_transaction_amount' => $original_transaction_amount,
            'currency' => $currency
        ];
        $this->db->where('id', $id);
        $this->db->update('report_conversion', $a_data);
    }

    public function get_list($period_base='datetime_updated', $start_date=FALSE, $end_date=FALSE, $keyword=FALSE, $campaign_id=FALSE, $status=FALSE, $sort=FALSE, $source=FALSE, $company=FALSE) {
        $this->load->library('qs');
        $this->qs->select('report_conversion.*, user.jelala_id, user.company');
        $this->qs->from('report_conversion');
        $this->qs->join('user', 'user.jelala_id = report_conversion.uid', 'left');

        if($start_date) $this->qs->where('report_conversion.'. $period_base. ' >=',date('Y-m-d',strtotime($start_date)).' 00:00:00');
        if($end_date) $this->qs->where('report_conversion.'. $period_base.' <=',date('Y-m-d',strtotime($end_date)).' 23:59:59');
        if($status) $this->qs->where('status', $status);
        if($campaign_id) $this->qs->where('campaign_id', $campaign_id);
        if($keyword) {
            $this->qs->group_start();
                $this->qs->like('uid', $keyword);
                $this->qs->or_like('conversion_id', $keyword);
                $this->qs->or_like('verification_id', $keyword);
            $this->qs->group_end();
        }
        if(!empty($source)) $this->qs->where('source', $source);
        if($sort) $this->qs->order_by($sort);
        if(!empty($company)) {
            $this->qs->group_start();
                $this->qs->where('company', $company);
                if($company == 'jelala') {
                    $this->qs->or_where('company IS NULL', NULL, TRUE);
                }
            $this->qs->group_end();
        } 
        $this->qs->where('status !=', 'INVALID');
        
        return $this->qs->get();
    }

    public function get_summary($period_base='datetime_updated', $start_date=FALSE, $end_date=FALSE, $keyword=FALSE, $campaign_id=FALSE, $status=FALSE, $currency='THB', $company=FALSE) {

        $this->db->select('COALESCE(SUM(reward), 0) as reward, COALESCE(SUM(transaction_amount), 0) as transaction_amount');

        $this->db->from('report_conversion');
        $this->db->join('user', 'user.jelala_id = report_conversion.uid', 'left');

        if($currency == 'THB') {
            $this->db->group_start();
            $this->db->where('currency', 'THB');
            $this->db->or_where('currency IS NULL', NULL, TRUE);
            $this->db->group_end();
        }else {
            $this->db->where('currency', $currency);
        }
        if($start_date) $this->db->where('report_conversion.'. $period_base. ' >=',date('Y-m-d',strtotime($start_date)).' 00:00:00');
        if($end_date) $this->db->where('report_conversion.'. $period_base.' <=',date('Y-m-d',strtotime($end_date)).' 23:59:59');
        if($status) $this->db->where('status', $status);
        if($campaign_id) $this->db->where('campaign_id', $campaign_id);
        
        if(!empty($company)) {
            $this->db->group_start();
                $this->db->where('company', $company);
                if($company == 'jelala') {
                    $this->db->or_where('company IS NULL', NULL, TRUE);
                }
            $this->db->group_end();
        } 

        $this->db->where('status !=', 'INVALID');
        if(empty($status)) {
            $this->db->where('status !=', 'REJECTED');
        }

        return $this->db->get()->row_array();
    }

    public function get_by_missing_id($id) {
        $this->db->where('missing_id', $id);
        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function get_by_order_id($verification_id, $uid) {
        $this->db->where('verification_id', $verification_id);
        $this->db->where('uid', $uid);

        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function get_by_order_id_with_missing_conversion($verification_id, $uid) {
        $this->db->where('missing_id > ', 0);
        $this->db->where('verification_id', $verification_id);
        $this->db->where('uid', $uid);

        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function delete_conversion($id) {
        $this->db->where('id', $id);
        $this->db->delete('report_conversion');
    }

    public function update_status_rejected($missing_id) {
        $this->db->where('missing_id', $missing_id);
        $this->db->update('report_conversion', ['status' => 'REJECTED']);
        return TRUE;
    }

    public function get_missing_order() {
        $this->db->where('missing_id >', 0);
        return $this->db->get('report_conversion')->result_array();
    }

}
