<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index(){
        redirect('cms/campaign/list');
    }

    private function _highlight_list() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $this->load->model('campaign_model');
        $campaigns = $this->campaign_model->get_highlight_list();

        return $this->format->map('cms/campaign/list', $campaigns);
    }

    public function list() {
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
            'a_campaign' => $a_campaign,
            'a_highlight_campaign' => $this->_highlight_list(),
            'status' => $status,
            'keyword' => $keyword
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function edit($id) {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $this->load->model('campaign_model');
        $campaign = $this->campaign_model->get_by_id($id);

        $default_rewards = $this->campaign_model->get_default_reward($id);
        $category_rewards = $this->campaign_model->get_category_reward($id);
        $custom_rewards = $this->campaign_model->get_custom_reward($id);

        $a_header_data = [
            'page' => 'edit campaign : ' . $campaign['name'],
            'a_admin' => $a_admin
        ];

        $a_data = [
            'campaign' => $campaign,
            'default_rewards' => $default_rewards,
            'category_rewards' => $category_rewards,
            'a_custom_reward' => $this->_set_custom_reward($custom_rewards)
        ];

        $this->head->js_add('js/campaign.js');

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/edit', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function update() {
        $custom_rewards = $this->input->post('custom_rewards');
    }

    private function _set_custom_reward($custom_rewards) {

        $default = [
            'default' => [
                'tier1' => NULL,
                'tier2' => NULL,
                'tier3' => NULL,
                'tier4' => NULL
            ],
            'category' => [
                'tier1' => NULL,
                'tier2' => NULL,
                'tier3' => NULL,
                'tier4' => NULL
            ],
            'customer_type' => [
                'tier1' => NULL,
                'tier2' => NULL,
                'tier3' => NULL,
                'tier4' => NULL
            ]
        ];

        $set_custom_rewards = (empty($custom_rewards)) ? $default : [];
        foreach($custom_rewards as $custom_reward) {
            $set_custom_rewards[$custom_reward['type']] = [];
            foreach($custom_reward as $key => $value) {
                if(in_array($key, ['campaign_id', 'type', 'datetime_updated'])) continue;
                $set_custom_rewards[$custom_reward['type']][$key] = $value;
            }
        }

        return $set_custom_rewards;
    }
    
}
