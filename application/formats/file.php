<?php

if(empty($data)) return (object) [];

$exploded = explode('/', $data['file_path']);
$file_name = end($exploded);

return [
    'id' => $data['id'],
    'url' => upload_base_url() . $data['file_path'],
    'file_name' => empty($file_name) ? '' : $file_name,
    'file_type' => empty($data['file_type']) ? '' : $data['file_type'],
    'file_size' => empty($data['file_size']) ? '' : $data['file_size'],
    'file_data' => empty($data['file_data']) ? (object) [] : json_decode($data['file_data']),
];