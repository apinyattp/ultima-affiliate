<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Optimise extends MY_Controller {

    public function __construct () {
        $this->_ci =& get_instance();

        $this->_ci->load->library('gateway');

        $this->_ci->load->config('affiliate/optimise');
        $this->aid = $this->_ci->config->item('aid');
        $this->api_key = $this->_ci->config->item('api_key');
    }

    protected $site_url = 'https://public.api.optimisemedia.com/v1/';
    protected $agency_id = 118;
    protected $aid;
    protected $api_key;


    private function _header() {
        $header = [
            'apikey: ' . $this->api_key,
        ];

        return $header;
    }

    public function campaign_code($id) {
        $_id = sprintf("%08d", $id);
        return "OP{$_id}";
    }

    public function campaigns($offset=0, $limit=50) {
        $url = "{$this->site_url}campaigns/";

        $data = [
            'contactId' => $this->aid,
            'agencyId' => $this->agency_id,
            'statuses' => 'live',
            'offset' => $offset,
            'limit' => $limit,
        ];

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);
        return $response;
    }

    public function conversion($fromDate, $toDate, $offset=0, $limit=50, $campaignId=NULL, $conversionStatuses=NULL, $dateField=NULL) {
        $url = "{$this->site_url}conversions/";

        $format = 'Y-m-d';

        $data = [
            'contactId' => $this->aid,
            'agencyId' => $this->agency_id,
            'fromDate' => date($format, strtotime($fromDate)),
            'toDate' => date($format, strtotime($toDate)),
            'dateField' => empty($dateField) ? 'lastModifiedDate' : $dateField,
        ];
        if ($campaignId) $data['campaignId'] = $campaignId;
        if ($conversionStatuses) $data['conversionStatuses'] = $conversionStatuses;

        $header = $this->_header();
        $response = json_decode($this->_ci->gateway->curl_get($url, $data, $header), TRUE);

        return $response;
    }
}
