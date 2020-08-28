<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class campaign_model extends CI_Model {

    protected $table_name = "banner";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_id($campaign_id, $status=FALSE) {
        $this->db->where('id', $campaign_id);
        if($status) $this->db->where('status', $status);
        $this->db->limit(1);
        $this->db->join('campaign_data', 'campaign.id = campaign_data.campaign_id');
        return $this->db->get('campaign')->row_array();
    }

    public function get_data_by_id($campaign_id) {
        $this->db->where('campaign_id', $campaign_id);
        $this->db->limit(1);
        return $this->db->get('campaign_data')->row_array();
    }

    public function get_list($keyword=FALSE, $status=FALSE, $sort=FALSE) {
        $this->load->library('qs');
        if($status) $this->qs->where('status', $status);
        if($sort) $this->qs->order_by($sort);
        if($keyword) {
            $this->qs->group_start();
                $this->qs->like('name', $keyword);
            $this->qs->group_end();
        }
        $this->qs->join('campaign_data', 'campaign.id = campaign_data.campaign_id');
        return $this->qs->get('campaign');
    }

    public function get_highlight_list() {
        $this->db->where('sort !=', 0);
        $this->db->order_by('sort ASC');
        $this->db->join('campaign_data', 'campaign.id = campaign_data.campaign_id');
        return $this->db->get('campaign')->result_array();
    }

    public function insert($id) {
        $this->db->insert('campaign', ['id' => $id]);
        return $this->db->insert_id();
    }

    public function update($campaign_id, $display_name, $image_file_id=NULL, $cashback, $condition_do, $condition_dont, $note) {
        $a_set = [
            'id' => $campaign_id,
            'display_name' => $display_name,
            'image_file_id' => $image_file_id,
            'cashback' => $cashback,
            'condition_do' => $condition_do,
            'condition_dont' => $condition_dont,
            'note' => $note,
        ];

        $this->db->where('id', $campaign_id);
        $this->db->update('campaign', $a_set);
    }

    public function update_data($campaign_id, $name, $source, $url, $type, $startDate=NULL, $endDate=NULL, $selfConversion, $pointBack, $imageUrl, $description, $englishDescription, $customCreativesAvailable, $seoContentAvailable, $productFeedAvailable, $quickLinkAvailable, $quicklink=NULL, $affiliationStatus, $affiliatedDate, $currency) {
        $a_set = [
            'campaign_id' => $campaign_id,
            'name' => $name,
            'source' => $source,
            'url' => $url,
            'type' => $type,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selfConversion' => $selfConversion,
            'pointBack' => $pointBack,
            'imageUrl' => $imageUrl,
            'description' => $description,
            'englishDescription' => $englishDescription,
            'customCreativesAvailable' => $customCreativesAvailable,
            'seoContentAvailable' => $seoContentAvailable,
            'productFeedAvailable' => $productFeedAvailable,
            'quickLinkAvailable' => $quickLinkAvailable,
            'quicklink' => $quicklink,
            'affiliationStatus' => $affiliationStatus,
            'affiliatedDate' => $affiliatedDate,
            'currency' => $currency
        ];
        $campaign_data = $this->get_data_by_id($campaign_id);
        if(empty($campaign_data)) {
            $this->db->insert('campaign_data', $a_set);
        }else {
            $this->db->where('campaign_id', $campaign_id);
            $this->db->update('campaign_data', $a_set);
        }
    }

    // UPDATE STATUS ACTIVE / INACTIVE
    public function update_status($campaign_id, $status) {
        $this->db->set('status', $status);
        $this->db->where('id', $campaign_id);
        $this->db->update('campaign');
    }

    // DEFAULT REWARD
    public function get_default_reward($campaign_id) {
        $this->db->where('campaign_id', $campaign_id);
        return $this->db->get('campaign_default_reward')->result_array();
    }

    public function update_default_reward($campaign_id, $a_default_reward) {
        $this->db->where('campaign_id', $campaign_id);
        $this->db->delete('campaign_default_reward');

        $this->db->insert_batch('campaign_default_reward', $a_default_reward);
    }

    // CATEGORY REWARD
    public function get_category_reward($campaign_id) {
        $this->db->where('campaign_id', $campaign_id);
        return $this->db->get('campaign_category_reward')->result_array();
    }

    public function update_category_reward($campaign_id, $category_reward_id, $type, $reward, $name=TRUE) {
        $a_set = [
            'id' => $category_reward_id,
            'campaign_id' => $campaign_id,
            'name' => $name,
            'type' => $type,
            'reward' => $reward
        ];
        $this->db->replace('campaign_category_reward', $a_set);
    }

    public function update_category($campaign_id, $a_category) {
        $this->db->where('campaign_id', $campaign_id);
        $this->db->delete('campaign_category');

        $this->db->insert_batch('campaign_category', $a_category);
    }

    // CUSTOM REWARD
    public function get_custom_reward($campaign_id) {
        $this->db->where('campaign_id', $campaign_id);
        return $this->db->get('campaign_custom_reward')->result_array();
    }

    public function insert_custom_reward($campaign_id, $type) {
        $a_set = [
            'campaign_id' => $campaign_id,
            'type' => $type
        ];
        $this->db->insert('campaign_custom_reward', $a_set);
    }

    public function update_custom_reward($campaign_id, $type, $a_set) {


        foreach($a_set as $key => $value) {
            if(empty(doubleval($value))) {
                $a_set[$key] = NULL;
            }
        }

        $a_set['campaign_id'] = $campaign_id;
        $a_set['type'] = $type;

        $this->db->replace('campaign_custom_reward', $a_set);
    }

    // PIN HIGHLIGHT
    public function is_pin($campaign_id) {
        $this->db->where('id', $campaign_id);
        $this->db->where('sort !=', 0);
        return (bool) $this->db->get('campaign')->row_array();
    }

    public function pin_count() {
        $this->db->where('sort !=', 0);
        return $this->db->count_all_results('campaign');
    }

    public function update_pin($campaign_id, $type) {
        $this->db->select('MAX(sort) sort');
        $max_sort = $this->db->get('campaign')->row('sort');

        if($type == 'pin') {
            if(!empty($this->is_pin($campaign_id))) return TRUE;
            $this->db->update('campaign', ['sort' => $max_sort + 1], ['id' => $campaign_id]);
        }else {
            $this->db->update('campaign', ['sort' => 0], ['id' => $campaign_id]);
        }
    }
}
