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
    'status_reject' => $data['status_reject'] == 'rejected' ? 'Y' : 'N',
    'source' => $data['source'],
    'datetime_created' => $data['datetime_created'],
    'datetime_updated' => $data['datetime_updated']
];
