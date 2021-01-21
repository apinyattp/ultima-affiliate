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
        $period_base = $this->input->get('period_base');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');
        $source = $this->input->get('source');

        $period_base = empty($period_base) ? 'datetime_updated' : $period_base;
        // $start_date = empty($start_date) ? date('Y-m-d') : $start_date;
        // $end_date = empty($end_date) ? date('m/d/Y') : $end_date;

        $page = max(1, $page);
        $perpage = empty($perpage) ? 10 : $perpage;

        $a_sort = [
            'conversion_id_desc' => 'conversion_id DESC',
            'conversion_time_asc' => 'conversion_time ASC',
            'conversion_time_desc' => 'conversion_time DESC',
            'datetime_updated_asc' => 'datetime_updated ASC, conversion_time DESC',
            'datetime_updated_desc' => 'datetime_updated desc, conversion_time DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'datetime_updated_desc';

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $status = (isset($a_status[$status])) ? $a_status[$status] : NULL;

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list($period_base, $start_date, $end_date, $keyword, $campaign_id, $status, $a_sort[$sort], $source);

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('cms/report/conversion/list');

        $this->load->model('campaign_model');
        $qs_campaign = $this->campaign_model->get_list(FALSE, FALSE, FALSE, 'name ASC');

        $a_campaign = $qs_campaign->result('cms/campaign/list', TRUE);

        // GET SUMMARY
        $a_summary = [
            'pending' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'PENDING'),
            'approved' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'APPROVED'),
            'rejected' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'REJECTED'),
            'total' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id)
        ];

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'a_summary' => $a_summary,
            'a_conversion' => $a_conversion,
            'a_campaign' => $a_campaign['lists'],
            'keyword' => $keyword,
            'status' => $status,
            'period_base' => $period_base,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'campaign_id' => $campaign_id
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/list/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function detail($id) {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $this->load->model('report_conversion_model');
        $conversion = $this->report_conversion_model->get_by_id($id);
        if(empty($conversion)) redirect('cms/report/list');

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'conversion' => $this->format->run('cms/report/conversion/detail', $conversion)
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/detail/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function export() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $period_base = $this->input->get('period_base');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $source = $this->input->get('source');

        $period_base = empty($period_base) ? 'datetime_updated' : $period_base;

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $status = (isset($a_status[$status])) ? $a_status[$status] : NULL;

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list($period_base, $start_date, $end_date, $keyword, $campaign_id, $status, FALSE, $source);

        $this->load->library('qs');

        $a_header = [
            'Conversion ID',
            'Campaign',
            'Uid',
            'Cashback',
            'Transaction Amount',
            'Transaction ID',
            'Click Time',
            'Conversion Time',
            'Confirmation Time',
            'Updaeted Time',
            'Status'
        ];

        $qs_conversion->export('conversion_report', 'csv', $a_header, 'cms/report/conversion/export');

    }

}
