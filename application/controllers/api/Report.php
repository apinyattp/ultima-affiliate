<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        redirect('cms/report/list');
    }

    public function list() {
        if(($this->_api_authorization()) !== TRUE) return $this->_echo_json(E::PERMISSION_DENIED);

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $campaign_id = $this->input->get('campaign_id');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');

        $page = max(1, $page);
        $perpage = empty($perpage) ? 100 : $perpage;

        $a_sort = [
            'conversion_id_desc' => 'conversion_id DESC',
            'conversion_time_asc' => 'conversion_time ASC',
            'conversion_time_desc' => 'conversion_time DESC',
            'datetime_updated_asc' => 'datetime_updated ASC, id DESC',
            'datetime_updated_desc' => 'datetime_updated desc, id DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'datetime_updated_desc';

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $status = (isset($a_status[$status])) ? $a_status[$status] : NULL;

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list(
            'datetime_updated',
            $start_date, 
            $end_date, 
            $keyword, 
            $campaign_id, 
            $status, 
            $a_sort[$sort]
        );

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('api/report/list');

        return $this->_echo_json(E::SUCCESS, $a_conversion);
    }

}
