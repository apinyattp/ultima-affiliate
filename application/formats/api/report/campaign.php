<?php
if (empty($data)) {
    return [
        'id' => 0,
        'display_name' => '',
    ];
}

return [
    'id' => $data['id'],
    'display_name' => empty($data['display_name']) ? $data['name'] : $data['display_name']
];