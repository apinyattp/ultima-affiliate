<?php

$default_reward = $this->campaign_model->get_default_reward($data['id']);

return [
    'id' => $data['id'],
    'name' => $data['name'],
    'image_url' => $data['imageUrl'],
    'default_reward' => $default_reward,
    'status' => $data['status'],
    'affiliated_date' => $data['affiliatedDate'],
    'is_highlight' => empty($data['sort']) ? 0 : 1,
    'deleted' => $data['deleted']
];