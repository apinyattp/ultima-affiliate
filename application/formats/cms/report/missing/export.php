<?php

$this->load->model('campaign_model');
$a_campaign = $this->campaign_model->get_by_id($data['campaign_id']);

return [
    'company' => empty($data['company']) ? 'jelala' : $data['company'],
    'campaign_name' => $a_campaign['name'],
    'uid' => $data['uuid'],
    'amount' => $data['amount'],
    'order_id' => $data['order_id'],
    'order_date' => date('Y-m-d H:i:s', strtotime($data['order_date'])),
    'status' => $data['status'],
    'datetime_updated' => $data['datetime_updated']
];