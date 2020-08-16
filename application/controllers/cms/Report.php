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
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');

        $page = max(1, $page);
        $perpage = empty($perpage) ? 10 : $perpage;

        $a_sort = [
            'conversion_id_desc' => 'conversion_id DESC',
            'conversion_time_asc' => 'conversion_time ASC',
            'conversion_time_desc' => 'conversion_time DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'conversion_id_desc';

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list($keyword, $campaign_id, $status, $a_sort[$sort]);

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('cms/report/conversion/list');

        $this->load->model('campaign_model');
        $qs_campaign = $this->campaign_model->get_list(FALSE, FALSE, 'name ASC');

        $a_campaign = $qs_campaign->result('cms/campaign/list', TRUE);

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'a_conversion' => $a_conversion,
            'a_campaign' => $a_campaign['lists']
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/list', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function search() {

    }

}
