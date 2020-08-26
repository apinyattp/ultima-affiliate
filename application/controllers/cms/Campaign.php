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

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->form_validation->set_rules('display_name', 'Display Name', 'required');
        $this->form_validation->set_rules('cashback', 'Cashback', 'required');
        $this->form_validation->set_rules('condition_do', 'Condition Do', 'required');
        $this->form_validation->set_rules('condition_dont', 'Condition Dont', 'required');
        $this->form_validation->set_rules('note', 'Note', 'required');

        $this->load->model('campaign_model');
        if ($this->form_validation->run() == FALSE) {

            $campaign = $this->campaign_model->get_by_id($id);

            $a_header_data = [
                'page' => 'edit campaign : ' . $campaign['name'],
                'a_admin' => $a_admin
            ];

            $a_data = $this->format->run('cms/campaign/set/main', $campaign);

            $post_data = $this->input->post();
            $set_data = array_replace_recursive($a_data, $post_data);

            $this->head->js_add('js/campaign.js');
            $this->load->view('cms/template/header', $a_header_data);
            $this->load->view('cms/campaign/edit/main', $set_data);
            $this->load->view('cms/template/footer');
        } else {

            $display_name = $this->input->post('display_name');
            $cashback = $this->input->post('cashback');
            $condition_do = $this->input->post('condition_do');
            $condition_dont = $this->input->post('condition_dont');
            $note = $this->input->post('note');
            $a_custom_reward = $this->input->post('a_custom_reward');

            $this->campaign_model->update($id, $display_name, NULL, $cashback, $condition_do, $condition_dont, $note);

            $this->campaign_model->update_custom_reward($id, 'default', $a_custom_reward['default']);
            $this->campaign_model->update_custom_reward($id, 'category', $a_custom_reward['category']);
            $this->campaign_model->update_custom_reward($id, 'customer_type', $a_custom_reward['customer_type']);

            $this->list();
        }
    }

    // private function _validate_field($data, $a_field, $id=NULL) {
    //     $a_set = [];
    //     foreach($a_field as $field) {
    //         $set_data = NULL;
    //         if(!is_null($data[$field])) {
    //             $value = $data[$field];
    //             switch ($field) {
    //                 case 'display_name':
    //                     if() return $this->_echo_json(E::NOT_FOUND_CONTENT,['field' => 'thumbnail_image_file_id']);
    //                     $set_data = $value;
    //                     break;
    //                 case 'cashback':
    //                     $set_data = date('Y-m-d', strtotime($value));
    //                     break;
    //                 default:
    //                     $set_data = $value;
    //                     break;
    //             }
    //         }
    //         $a_set[$field] = $set_data;
    //     }

    //     return $a_set;
    // }

}
