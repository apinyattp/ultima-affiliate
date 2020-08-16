<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');

        $page = max(1, $page);
        $perpage = empty($perpage) ? 10 : $perpage;

        $a_sort = [
            'name_asc' => 'name ASC',
            'name_desc' => 'name DESC',
            'id_desc' => 'id DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'id_desc';

        $a_header_data = [
            'page' => 'campaign',
            'a_admin' => $a_admin
        ];

        $this->load->model('campaign_model');
        $qs = $this->campaign_model->get_list($keyword, $status, $a_sort[$sort]);

        $this->load->library('qs');
        $qs->page($page, $perpage);

        $a_campaign = $qs->result('cms/campaign/list');

        $a_data = [
            'a_campaign' => $a_campaign
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/list', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function edit($id) {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $this->load->model('campaign_model');
        $campaign = $this->campaign_model->get_by_id($id);
        
        $a_header_data = [
            'page' => 'edit campaign : ' . $campaign['name'],
            'a_admin' => $a_admin
        ];

        $a_data = [
            'campaign' => $campaign
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/edit', $a_data);
        $this->load->view('cms/template/footer');
    }

    
}
