<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Merchant_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_token($token, $is_active=1) {
        $this->db->where('token', $token);
        if ($is_active !== NULL) $this->db->where('is_active', $is_active);
        return $this->db->get('merchant')->row_array();
    }

}
