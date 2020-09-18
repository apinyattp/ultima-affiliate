<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admitad extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('gateway');
        $this->load->library('admitad_api');
    }

    public function me() {
        $me = $this->admitad_api->me();

        $this->_echo_json(E::SUCCESS, $me);
    }

    public function advcampaigns() {
        $advcampaigns = $this->admitad_api->advcampaigns();

        $this->_echo_json(E::SUCCESS, $advcampaigns);
    }

    public function advcampaign($campaign_id) {
        $advcampaign = $this->admitad_api->advcampaign($campaign_id);

        $this->_echo_json(E::SUCCESS, $advcampaign);
    }

    public function report() {

        $campaign_id = $this->input->get('campaign_id');
        $date_start = $this->input->get('date_start');
        $date_end = $this->input->get('date_end');
        $order_by = $this->input->get('order_by');

        // $date_start = empty($date_start) ? date('d.m.Y') : $date_start;
        // $date_end = empty($date_end) ? date('d.m.Y', strtotime('+1 days')) : $date_end;

        $report = $this->admitad_api->report($campaign_id, $date_start, $date_end, $order_by);

        $field = [
            'comment',
            'click_user_ip',
            'currency',
            'website_name',
            'status_updated',
            'id',
            'advcampaign_id',
            'subid1',
            'subid3',
            'subid2',
            'subid4',
            'click_user_referer',
            'click_date',
            'action_id',
            'status',
            'order_id',
            'cart',
            'conversion_time',
            'paid',
            'payment',
            'click_country_code',
            'advcampaign_name',
            'tariff_id',
            'keyword',
            'closing_date',
            'positions',
            'subid',
            'action_date',
            'processed',
            'action_type',
            'action'
        ];

        $field2 = [
            'comment' => '',
            'click_user_ip' => '',
            'currency' => '',
            'website_name' => '',
            'status_updated' => '',
            'id' => '',
            'advcampaign_id' => '',
            'subid1' => '',
            'subid3' => '',
            'subid2' => '',
            'subid4' => '',
            'click_user_referer' => '',
            'click_date' => '',
            'action_id' => '',
            'status' => '',
            'order_id' => '',
            'cart' => '',
            'conversion_time' => '',
            'paid' => '',
            'payment' => '',
            'click_country_code' => '',
            'advcampaign_name' => '',
            'tariff_id' => '',
            'keyword' => '',
            'closing_date' => '',
            'positions' => '',
            'subid' => '',
            'action_date' => '',
            'processed' => '',
            'action_type' => '',
            'action'
        ];

        $this->_echo_json(E::SUCCESS, $report);
    }

}
