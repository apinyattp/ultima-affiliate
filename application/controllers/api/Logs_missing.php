<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs_missing extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        redirect('cms/report/list');
    }

    public function list() {
        if(($auth = $this->_api_authorization()) === FALSE) return $this->_echo_json(E::PERMISSION_DENIED);

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');

        $page = max(1, $page);
        $perpage = empty($perpage) ? 100 : $perpage;

        $this->load->model('logs_missing_model');
        $qs_logs_missing = $this->logs_missing_model->get_list($start_date, $end_date);

        $this->load->library('qs');
        $qs_logs_missing->page($page, $perpage);

        $a_logs_missing = $qs_logs_missing->result('api/logs_missing/list');
        return $this->_echo_json(E::SUCCESS, $a_logs_missing);
    }
}
