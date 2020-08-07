<?php
$token = $this->admin_authorization->generate_token($data['id'], $data['password_token'], [], 10);

$a_admin = $this->format->run('module/admin/admin/login_admin_data', $data);

$a_result = [
    'token' => $token,
    'admin' => $a_admin,
];

if($this->load->find_module('admin_role') !== FALSE) {
    $a_result['role'] = $data['role'];
}

if($this->load->find_module('admin_permission') !== FALSE) {
    $a_result['permission'] = json_decode($data['permission']);
}

return $a_result;
