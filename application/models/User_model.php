<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create_user_relation($uuid, $company) {
        $a_data = [
            'uuid' => $uuid,
            'jelala_id' => $company . '_' . hash('sha256', md5($company. '_'. $uuid . '_' . time())),
            'company' => $company
        ];
        $this->db->insert('user', $a_data);
        return $a_data;
    }

    public function check_by_uuid($uuid) {
        $this->db->select('uuid,jelala_id,company');
        $this->db->where('uuid', $uuid);
        return $this->db->get('user')->row_array();
    }

    public function get_by_jelala_id($jelala_id) {
        $this->db->select('uuid,jelala_id,company');
        $this->db->where('jelala_id', $jelala_id);
        return $this->db->get('user')->row_array();
    }


}