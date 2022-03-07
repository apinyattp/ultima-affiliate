<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Missing_conversion extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->config('affiliate/main');
    }

    public function index(){
        redirect('cms/missing_conversion/list');
    }

    public function list() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        if($a_admin['role'] != 'admin')  redirect('cms/admin');

        $this->head->js_add('js/missing/list.js');

        $keyword = $this->input->get('keyword');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $company = $this->input->get('company');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');
        $source = $this->input->get('source');
        $status_filter = $this->input->get('status');
        $status_reject = $this->input->get('status_reject');
        $start_amount = (float)$this->input->get('start_amount');
        $end_amount = (float)$this->input->get('end_amount');

        if($start_amount <= 0) $start_amount = 0;
        if($end_amount <= 0) $end_amount = 0;

        $page = max(1, $page);
        $perpage = empty($perpage) ? 10 : $perpage;

        $a_sort = [
            'order_date_asc' => 'order_date ASC',
            'order_date_desc' => 'order_date DESC',
            'datetime_updated_asc' => 'datetime_updated ASC',
            'datetime_updated_desc' => 'datetime_updated DESC',
            'datetime_created_asc' => 'datetime_created ASC',
            'amount_asc' => 'amount ASC',
            'amount_desc' => 'amount DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'datetime_created_asc';

        $this->load->model('missing_conversion_model');
        $qs_conversion = $this->missing_conversion_model->get_list($start_date, $end_date, $keyword, $campaign_id, $a_sort[$sort], $company, $source, $status_filter, $status_reject, $start_amount, $end_amount);

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('cms/report/missing/list');

        $this->load->model('campaign_model');
        $qs_campaign = $this->campaign_model->get_list(FALSE, FALSE, FALSE, 'name ASC');

        $a_campaign = $qs_campaign->result('cms/campaign/list', TRUE);

        $a_header_data = [
            'page' => 'missing_conversion',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'a_campaign' => $a_campaign['lists'],
            'keyword' => $keyword,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'campaign_id' => $campaign_id,
            'role' => $a_admin['role'],
            'a_conversion' => $a_conversion,
            'company' => $company,
            'source' => $source,
            'companies' => $this->config->item('companies'),
            'sort' => $sort,
            'a_sort' => $a_sort,
            'status_filter' => $status_filter,
            'status_reject' => $status_reject,
            'start_amount' => empty($start_amount) ? '' : $start_amount,
            'end_amount' => empty($end_amount) ? '' : $end_amount,
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/missing_conversion/list/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function export() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        if($a_admin['role'] != 'admin')  redirect('cms/admin');

        $keyword = $this->input->get('keyword');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $company = $this->input->get('company');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');
        $source = $this->input->get('source');
        $status_filter = $this->input->get('status');
        $status_reject = $this->input->get('status_reject');
        $start_amount = (float)$this->input->get('start_amount');
        $end_amount = (float)$this->input->get('end_amount');

        if($start_amount <= 0) $start_amount = 0;
        if($end_amount <= 0) $end_amount = 0;

        $a_sort = [
            'order_date_asc' => 'order_date ASC',
            'order_date_desc' => 'order_date DESC',
            'datetime_updated_asc' => 'datetime_updated ASC',
            'datetime_updated_desc' => 'datetime_updated DESC',
            'datetime_created_asc' => 'datetime_created ASC',
            'datetime_created_desc' => 'datetime_created DESC',
            'amount_asc' => 'amount ASC',
            'amount_desc' => 'amount DESC',
        ];

        if(!isset($a_sort[$sort])) $sort = 'datetime_created_asc';

        $this->load->model('missing_conversion_model');
        $qs_conversion = $this->missing_conversion_model->get_list($start_date, $end_date, $keyword, $campaign_id, $a_sort[$sort], $company, $source, $status_filter, $status_reject, $start_amount, $end_amount);

        $this->load->library('qs');

        $a_header = [
            'Company',
            'Campaign',
            'uid',
            'Amount',
            'Order ID',
            'Order Date',
            'Status',
            'Status Rejected',
            'Source',
            'Datetime Created',
            'Datetime Updated'
        ];

        $qs_conversion->export('missing_conversion_report', 'csv', $a_header, 'cms/report/missing/export');

    }

    public function update_rejected() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        if($a_admin['role'] != 'admin')  return $this->_echo_json(E::NOT_FOUND_ACCOUNT);

        $missing_id = $this->input->post('id');

        $this->load->model('missing_conversion_model');
        $this->missing_conversion_model->update_status_rejected($missing_id);

        $this->load->model('report_conversion_model');
        $this->report_conversion_model->update_status_rejected($missing_id);

        return $this->_echo_json(E::SUCCESS);
    }

    public function update_status() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        if($a_admin['role'] != 'admin')  return $this->_echo_json(E::NOT_FOUND_ACCOUNT);

        $missing_id = $this->input->post('id');

        $this->load->model('missing_conversion_model');

        $a_missing_data = $this->missing_conversion_model->get_by_missing_id($missing_id);

        $status = 'new';
        if($a_missing_data['status'] == 'new') {
            $status = 'send_to_affiliate';
        }

        $this->missing_conversion_model->update_status($missing_id, $status);

        return $this->_echo_json(E::SUCCESS, ['status' => $status]);
    }

}
