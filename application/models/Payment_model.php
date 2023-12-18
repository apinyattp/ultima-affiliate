<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_invoice_no($invoice_no, $source) {
        $this->db->where('invoice_no', $invoice_no)
                ->where('source', $source);
        return $this->db->get('payment')->row_array();
    }

    public function insert($invoice_no, $source, $amount_total, $amount_paid, $amount_vat, $amount_wht, $datetime_paid) {
        $a_data = [
            'invoice_no' => $invoice_no,
            'source' => $source,
            'amount_total' => $amount_total,
            'amount_paid' => $amount_paid,
            'amount_vat' => $amount_vat,
            'amount_wht' => $amount_wht,
            'datetime_paid' => $datetime_paid
        ];
        $this->db->insert('payment', $a_data);
        return $this->db->insert_id();
    }

    public function update_amount_member($id, $amount_member) {
        $this->db->where('id', $id);
        $this->db->update('payment', ['amount_member' => $amount_member]);
    }

}
