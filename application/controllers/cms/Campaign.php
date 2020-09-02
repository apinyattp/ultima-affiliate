<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('campaign_model');

        $this->load->library('jelala');
    }

    public function index(){
        redirect('cms/campaign/list');
    }

    private function _highlight_list() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

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
            'datetime_created_asc' => 'campaign.datetime_created ASC',
            'datetime_updated_asc' => 'campaign.datetime_updated ASC',
            'datetime_updated_desc' => 'campaign.datetime_updated DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'datetime_updated_desc';

        $a_header_data = [
            'page' => 'campaign',
            'a_admin' => $a_admin
        ];

        $qs = $this->campaign_model->get_list($keyword, $status, $a_sort[$sort]);

        $this->load->library('qs');
        $qs->page($page, $perpage);

        $a_campaign = $qs->result('cms/campaign/list');

        $a_data = [
            'search_filter' => [
                'status' => $status,
                'keyword' => $keyword,
            ],
            'a_campaign' => $a_campaign,
            'a_highlight_campaign' => $this->_highlight_list()
        ];

        $this->head->js_add('js/campaign/list.js');
        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/campaign/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function edit($id) {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        // $this->form_validation->set_rules('display_name', 'Display Name', 'required');
        // $this->form_validation->set_rules('cashback', 'Cashback', 'required');
        // $this->form_validation->set_rules('status', 'Status', 'required');
        // $this->form_validation->set_rules('condition_do', 'Condition Do', 'required');
        // $this->form_validation->set_rules('condition_dont', 'Condition Dont', 'required');
        // $this->form_validation->set_rules('note', 'Note', 'required');

        if ($this->form_validation->run() == FALSE) {

            $campaign = $this->campaign_model->get_by_id($id);

            $a_header_data = [
                'page' => 'edit campaign : ' . $campaign['name'],
                'a_admin' => $a_admin
            ];

            $a_data = $this->format->run('cms/campaign/set/main', $campaign);

            $post_data = $this->_set_return_post_data();

            // SET FORM DATA
            $set_data = array_replace_recursive($a_data, $post_data);

            // ADD HEAD
            $this->head->js_add('js/campaign/edit.js');

            $this->load->view('cms/template/header', $a_header_data);
            $this->load->view('cms/campaign/edit/main', $set_data);
            $this->load->view('cms/template/footer');
        } else {

            $display_name = $this->input->post('display_name');
            $cashback = $this->input->post('cashback');
            $image_file_id = $this->input->post('logo_file_id');
            $condition_do = $this->input->post('condition_do');
            $condition_dont = $this->input->post('condition_dont');
            $note = $this->input->post('note');
            $category_rewards = $this->input->post('category_rewards');
            $a_custom_reward = $this->input->post('a_custom_reward');
            $status = $this->input->post('status');

            $this->load->model('module/file/file_model', 'file_model');
            if(!$this->file_model->verify($image_file_id, 'campaign_logo', $id)) return $this->_echo_json(E::INVALID_FORMAT, ['logo_file_id' => $image_file_id]);

            $this->campaign_model->update($id, $display_name, $image_file_id, $cashback, $status, $condition_do, $condition_dont, $note);

            $this->file_model->update_live($image_file_id, $id, TRUE);

            foreach($category_rewards as $key => $category_reward) {
                $this->campaign_model->update_category_custom_reward($key, $category_reward['custom_reward']);                
            }

            $this->campaign_model->update_custom_reward($id, 'default', $a_custom_reward['default']);
            $this->campaign_model->update_custom_reward($id, 'category', $a_custom_reward['category']);
            $this->campaign_model->update_custom_reward($id, 'customer_type', $a_custom_reward['customer_type']);

            $this->_update_jelala($id);

            $this->list();
        }
    }

    private function _set_return_post_data() {
        $post_data = $this->input->post();

        if(isset($post_data['category_rewards'])) {
            // SET category reward post data
            $category_rewards_set = [];
            foreach((array) $post_data['category_rewards'] as $key => $category_reward) {
                $category_rewards_set[] = [
                    'id' => $key,
                    'custom_reward' => $category_reward['custom_reward']
                ];
            }
            $post_data['category_rewards'] = $category_rewards_set;
        }

        return $post_data;
    }

    public function update_status() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $campaign_id = $this->input->post('campaign_id');
        $status = $this->input->post('status');

        $this->campaign_model->update_status($campaign_id, $status);

        $this->_update_jelala($campaign_id);
        
        return $this->_echo_json(E::SUCCESS);
    }

    public function update_comingsoon_status() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $campaign_id = $this->input->post('campaign_id');
        $coming_soon = $this->input->post('coming_soon');

        $this->campaign_model->update_comingsoon_status($campaign_id, $coming_soon);

        $this->_update_jelala($campaign_id);
        
        return $this->_echo_json(E::SUCCESS);
    }

    public function delete($campaign_id) {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $campaign = $this->campaign_model->get_by_id($campaign_id);
        if(empty($campaign)) {
            $this->output->set_status_header(400);
            return $this->_echo_json(E::NOT_FOUND_CONTENT);
        }

        $this->campaign_model->delete($campaign_id);

        // $this->jelala->delete_campaign($campaign_id);

        return $this->_echo_json(E::SUCCESS);
    }

    public function update_highlight_pin() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');

        $campaign_id = $this->input->post('campaign_id');
        $type = $this->input->post('type');

        $count = $this->campaign_model->pin_count();

        if($type == 'pin' && $count >= 6) {
            $this->output->set_status_header(400);
            return $this->_echo_json(E::CAMPAIGN_PIN_HIGHLIGHT_EXCEED_LIMIT);
        }

        $this->campaign_model->update_pin($campaign_id, $type);

        $this->_update_jelala($campaign_id);
        
        return $this->_echo_json(E::SUCCESS, ['type' => $type, 'query' => $this->db->last_query()]);
    }

    public function update_highlight_sort() {
        if(($auth = $this->_admin_authorization('admin')) !== TRUE) redirect('cms/admin');
        
        return $this->_echo_json(E::SUCCESS);
    }

    private function _update_jelala($ids) {
        // $this->jelala->update_campaign($ids);
    }

}
