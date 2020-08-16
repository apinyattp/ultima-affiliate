<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class campaign_model extends CI_Model {

    protected $table_name = "banner";

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_id($id, $status=FALSE) {
        $this->db->where('id', $id);
        if($status) $this->db->where('status', $status);
        $this->db->limit(1);
        return $this->db->get('campaign')->row_array();
    }

    public function update($id, $name, $source, $url, $quicklink, $image_url, $default_reward, $affiliated_date) {
        $a_data = [
            'id' => $id,
            'name' => $name,
            'source' => $source,
            'url' => $url,
            'quicklink' => $quicklink,
            'image_url' => $image_url,
            'default_reward' => $default_reward,
            'affiliated_date' => $affiliated_date
        ];
        $campaign = $this->get_by_id($id);
        if(empty($campaign)) {
            $this->db->insert('campaign', $a_data);
        }else {
            $this->db->where('id', $id);
            $this->db->update('campaign', $a_data);
        }
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
        return $this->qs->get('campaign');
    }

}
