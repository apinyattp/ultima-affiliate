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

    public function get_by_conversion_id($conversion_id) {
        $this->db->where('conversion_id', $conversion_id);
        $this->db->limit(1);
        return $this->db->get('report_conversion')->row_array();
    }

    public function update_by_conversion_id($conversion_id, $source, $uid, $site_id, $site_name, $campaign_id, $campaign_name, $customerType=NULL, $creative_id, $creative_name, $verification_id, $click_time, $conversion_time, $confirmation_time=NULL, $status, $reward, $transaction_amount, $session_id, $user_agent=NULL, $parameters=NULL, $products=NULL, $other_parameters=NULL) {
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
            'source' => $source
        ];
        $conversion = $this->get_by_conversion_id($conversion_id);
        if(empty($conversion)) {
            $this->db->insert('report_conversion', $a_data);
        }else {
            $this->db->where('conversion_id', $conversion_id);
            $this->db->update('report_conversion', $a_data);
        }
    }

    public function update($conversion_id, $a_data) {
        $this->db->where('conversion_id', $conversion_id);
        $this->db->update('report_conversion', $a_data);
    }

    public function update_status($conversion_id,$status, $confirmation_time, $reward, $transaction_amount) {
        $a_data = [
            'status' => $status,
            'confirmation_time' => $confirmation_time,
            'reward' => $reward,
            'transaction_amount' => $transaction_amount
        ];
        $this->db->where('conversion_id', $conversion_id);
        $this->db->update('report_conversion');
    }

    public function get_list($period_base='datetime_updated', $start_date=FALSE, $end_date=FALSE, $keyword=FALSE, $campaign_id=FALSE, $status=FALSE, $sort=FALSE) {
        $this->load->library('qs');
        if($start_date) $this->qs->where($period_base. ' >=',date('Y-m-d',strtotime($start_date)).' 00:00:00');
        if($end_date) $this->qs->where($period_base.' <=',date('Y-m-d',strtotime($end_date)).' 23:59:59');
        if($status) $this->qs->where('status', $status);
        if($campaign_id) $this->qs->where('campaign_id', $campaign_id);
        if($keyword) {
            $this->qs->group_start();
                // $this->qs->like('name', $keyword);
            $this->qs->group_end();
        }
        if($sort) $this->qs->order_by($sort);
        return $this->qs->get('report_conversion');
    }

}
