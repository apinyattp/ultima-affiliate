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
        if(($auth = $this->_api_authorization()) === FALSE) return $this->_echo_json(E::PERMISSION_DENIED);

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $campaign_id = $this->input->get('campaign_id');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');
        $company = $auth;

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
            $a_sort[$sort],
            FALSE,
            $company
        );

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('api/report/list');
        return $this->_echo_json(E::SUCCESS, $a_conversion);
    }

    public function get_missing_conversion() {
        if(($auth = $this->_api_authorization()) === FALSE) return $this->_echo_json(E::PERMISSION_DENIED);

        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');

        $page = max(1, $page);
        $perpage = empty($perpage) ? 100 : $perpage;

        $this->load->model('missing_conversion_model');

        $qs_conversion = $this->missing_conversion_model->get_list($start_date, $end_date);
        
        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('api/missing_conversion/list');
        return $this->_echo_json(E::SUCCESS, $a_conversion);
    }

    public function update_missing_conversion() {
        if(($auth = $this->_api_authorization()) === FALSE) return $this->_echo_json(E::PERMISSION_DENIED);

        $this->load->model('missing_conversion_model');
        $this->load->model('report_conversion_model');
        $this->load->model('campaign_model');

        $a_data = $this->input->post();
        $a_data['company'] = $auth;
        
        $a_data = $this->format->run('api/missing_conversion/create', $a_data);

        $id = $this->missing_conversion_model->update_missing_conversion($a_data);

        $check_conversion_exist = $this->report_conversion_model->get_by_order_id($a_data['order_id']);
        if(empty($check_conversion_exist)) {
            $a_conversion_missing = $this->report_conversion_model->get_by_missing_id($id);
        
            $a_campaign = $this->campaign_model->get_by_id($a_data['campaign_id']);
            $a_set_reward = $this->campaign_model->get_set_reward($a_data['campaign_id']);
    
            $reward = !empty($a_set_reward) ? $a_set_reward['existing'] : 0.1;
            $summary_reward = (int)$a_data['amount'] * ((int)$reward/100);
            
            $this->report_conversion_model->update_by_conversion_id2(
                $id,
                'involve_asia',
                $a_data['uuid'],
                '194802',
                'Jelala',
                $a_data['campaign_id'],
                !empty( $a_campaign) ? $a_campaign['display_name'] : '',
                NULL,
                NULL,
                NULL,
                $a_data['order_id'],
                $a_data['order_date'],
                $a_data['order_date'],
                NULL,
                'PENDING',
                $summary_reward,
                $summary_reward,
                $a_data['amount'],
                $a_data['amount'],
                'THB',
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                $id
            );
        }

        return $this->_echo_json(E::SUCCESS, ['id' => (int)$id]);
    }

}
