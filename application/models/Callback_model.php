<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Callback_model extends CI_Model {

    protected $table_name = "banner";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function create($data) {
        $a_data = [
            'data' => $data
        ];
        $this->db->insert('callback_data', $a_data);
    }

}
