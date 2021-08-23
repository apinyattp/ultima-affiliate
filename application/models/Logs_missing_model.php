<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_missing_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    public function insert_logs($id) {
        $a_data = [
            'report_conversion_id' => $id
        ];
        $this->db->insert('logs_missing', $a_data);
        return TRUE;
   }
    
    public function get_list($start_date=FALSE, $end_date=FALSE, $sort=FALSE) {
        $this->load->library('qs');
        if($start_date) $this->qs->where('datetime_updated >=',date('Y-m-d',strtotime($start_date)).' 00:00:00');
        if($end_date) $this->qs->where('datetime_updated <=',date('Y-m-d',strtotime($end_date)).' 23:59:59');
        if($sort) $this->qs->order_by($sort);
        
        return $this->qs->get('logs_missing');
    }
    
   
}
