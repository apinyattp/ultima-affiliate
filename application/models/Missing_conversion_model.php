<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Missing_conversion_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
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

}
