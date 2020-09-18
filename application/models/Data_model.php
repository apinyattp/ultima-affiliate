<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_date($source, $date) {
        $this->db->where('source', $source);
        $this->db->where('date', $date);
        return $this->db->get('data_currency_exchange_rate')->row_array();
    }

    public function create($source, $base, $target, $rate, $date) {
        $a_data = [
            'source' => $source,
            'base' => $base,
            'target' => $target,
            'rate' => $rate,
            'date' => $date
        ];
        $this->db->insert('data_currency_exchange_rate', $a_data);
    }

}
