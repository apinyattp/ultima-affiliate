<?php

$this->load->model('campaign_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);

return [
    'id' => $data['id'],
    'campaign_id' => $data['campaign_id'],
    'campaign_name' => empty($a_campaign) ? '' : $a_campaign['display_name'],
    'uid' => $data['uuid'],
    'company' => empty($data['company']) ? 'jelala' : $data['company'],
    'amount' => $data['amount'],
    'order_id' => $data['order_id'],
    'order_date' => date('Y-m-d H:i:s', strtotime($data['order_date'])),
    'status' => empty($data['status']) ? '' : $data['status'],
    'datetime_updated' => $data['datetime_updated']
];