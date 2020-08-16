<?php

return [
    'id' => $data['id'],
    'name' => $data['name'],
    'image_url' => $data['image_url'],
    'default_reward' => json_decode($data['default_reward'], TRUE),
    'status' => $data['status'],
    'affiliated_date' => $data['affiliated_date']
];