<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_shipping_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get($courier, $tracking) {
        $this->db->from('report_shipping')
                ->where('courier', $courier)
                ->where('tracking', $tracking)
                ->limit(1);
        return $this->db->get()->row_array();
    }

    public function save($source, $courier, $tracking) {
        $report_shipping = $this->get($courier, $tracking);
        if($report_shipping) return $report_shipping['id'];

        $a_data = [
            'source' => $source,
            'courier' => $courier,
            'tracking' => $tracking
        ];
        $this->db->insert('report_shipping', $a_data);

        return $this->db->insert_id();
    }

}
