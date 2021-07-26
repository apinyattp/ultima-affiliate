<?php

return [
    'uuid' => $data['uuid'],
    'campaign_id' => $data['campaign_id'],
    'order_id' => $data['order_id'],
    'amount' => $data['amount'],
    'order_date' => date('Y-m-d H:i:s', strtotime($data['order_date'])),
    'comefrom' => $data['company']
];