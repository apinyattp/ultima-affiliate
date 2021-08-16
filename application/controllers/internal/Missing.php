<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Missing extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function recal_missing() {
        $this->load->model('missing_conversion_model');
        $this->load->model('report_conversion_model');
        $this->load->model('campaign_model');
        
        $a_missing = $this->missing_conversion_model->get_list();

        $this->load->library('qs');
        $a_missing->page(1, 500);

        $a_conversion = $a_missing->result('api/missing_conversion/list');
        if(empty($a_conversion['lists'])) return;

        foreach($a_conversion['lists'] as $a_conversion_missing) {
            $a_campaign = $this->campaign_model->get_by_id($a_conversion_missing['campaign_id']);
            $a_set_reward = $this->campaign_model->get_set_reward($a_conversion_missing['campaign_id']);
    
            $reward = !empty($a_set_reward) ? $a_set_reward['existing'] : 0.1;
            $summary_reward = (int)$a_conversion_missing['amount'] * ((int)$reward/100);
            
            $this->report_conversion_model->update_by_conversion_id2(
                $a_conversion_missing['id'],
                'involve_asia',
                $a_conversion_missing['uid'],
                '194802',
                'Jelala',
                $a_conversion_missing['campaign_id'],
                !empty( $a_campaign) ? $a_campaign['display_name'] : '',
                NULL,
                NULL,
                NULL,
                $a_conversion_missing['order_id'],
                $a_conversion_missing['order_date'],
                $a_conversion_missing['order_date'],
                NULL,
                'PENDING',
                $summary_reward,
                $summary_reward,
                $a_conversion_missing['amount'],
                $a_conversion_missing['amount'],
                'THB',
                NULL,
                NULL,
                NULL,
                NULL,
                NULL,
                $a_conversion_missing['id']
            );
        }
    }
}