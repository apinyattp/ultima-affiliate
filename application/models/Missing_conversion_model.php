<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Missing_conversion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_list($start_date=FALSE, $end_date=FALSE, $keyword=FALSE, $campaign_id=FALSE, $sort=FALSE, $company=FALSE) {

        $this->load->library('qs');
        $this->qs->select('missing_conversion.*, user.jelala_id, user.company');
        $this->qs->from('missing_conversion');
        $this->qs->join('user', 'user.jelala_id = missing_conversion.uuid', 'left');
    
        if($start_date) $this->qs->where('missing_conversion.order_date >=',date('Y-m-d',strtotime($start_date)).' 00:00:00');
        if($end_date) $this->qs->where('missing_conversion.order_date <=',date('Y-m-d',strtotime($end_date)).' 23:59:59');
        if($campaign_id) $this->qs->where('missing_conversion.campaign_id', $campaign_id);
        if($keyword) {
            $this->qs->group_start();
                $this->qs->like('missing_conversion.uuid', $keyword);
                $this->qs->or_like('missing_conversion.order_id', $keyword);
            $this->qs->group_end();
        }
        if($sort) $this->qs->order_by($sort);
        if(!empty($company)) {
            $this->qs->group_start();
                $this->qs->where('company', $company);
                if($company == 'jelala') {
                    $this->qs->or_where('company IS NULL', NULL, TRUE);
                }
            $this->qs->group_end();
        } 
        return $this->qs->get();
    }

    public function update_missing_conversion($a_set) {
        $missing_conversion = $this->get_by_id($a_set['uuid'], $a_set['campaign_id'], $a_set['order_id']);
        if(empty($missing_conversion)) {
            $this->db->insert('missing_conversion', $a_set);
            $id = $this->db->insert_id();
        }else {
            $id = $missing_conversion['id'];
            $this->db->where('id', $id);
            $this->db->update('missing_conversion', $a_set);
        }
        return $id;
    }

    public function get_by_id($uuid, $campaign_id, $order_id) {
        $this->db->where('uuid', $uuid);
        $this->db->where('campaign_id', $campaign_id);
        $this->db->where('order_id', $order_id);

        return $this->db->get('missing_conversion')->row_array();
    }

    public function update_status_rejected($id) {
        $this->db->where('id', $id);
        $this->db->update('missing_conversion', ['status' => 'rejected']);
        return TRUE;
    }

}
