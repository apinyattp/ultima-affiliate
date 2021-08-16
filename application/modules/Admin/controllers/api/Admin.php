<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct () {
        parent::__construct();
    }

    public function login() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $this->load->model('module/admin/admin_model');
        $a_admin = $this->admin_admin_model->login($username, $password);

        if($a_admin === FALSE) return $this->_echo_json(E::AUTH_LOGIN_INVALID_USERNAME_OR_PASSWORD);

        $this->load->library('module/admin/authorization');
        $result = $this->format->run('module/admin/admin/login_success', $a_admin);

        return $this->_echo_json(E::SUCCESS, $result);
    }

    public function forgot_password() {
        $email = $this->input->post('email');

        $this->load->model('module/admin/admin_model');
        $a_admin = $this->admin_admin_model->get_by_email($email);
        if(empty($a_admin)) return $this->_echo_json(E::NOT_FOUND_ACCOUNT);

        $token = $this->admin_admin_model->forgot_password($a_admin['id']);

        $this->load->config('email');
        $a_config = $this->config->item('email_forgot_password');

        $subject = $a_config['subject'];
        $view = 'module/admin/email/forgot_password';
        $data = [
            'link' => $a_config['link'],
            'reset_password_token' => $token,
            'admin_id' => $a_admin['id']
        ];

        $this->_sendmail($subject, $email, $view, $data);

        return $this->_echo_json(E::SUCCESS);
    }

    public function reset_password() {
        $admin_id = $this->input->post('admin_id');
        $reset_password_token = $this->input->post('reset_password_token');
        $password = $this->input->post('password');

        $this->load->model('module/admin/admin_model');
        $a_admin = $this->admin_admin_model->get_by_id($admin_id);
        if(empty($a_admin)) return $this->_echo_json(E::NOT_FOUND_ACCOUNT);

        if($reset_password_token != $a_admin['reset_password_token']) return $this->_echo_json(E::INVALID_FORMAT, ['field' => 'token']);

        $a_admin['password_token'] = $this->admin_admin_model->update_password($admin_id, $password);

        $this->load->library('module/admin/authorization');
        $result = $this->format->run('module/admin/admin/login_success', $a_admin);

        return $this->_echo_json(E::SUCCESS, $result);
    }

    public function myprofile() {
        if(($auth = $this->_authorization()) !== TRUE) return $this->_echo_json($auth);

        $a_admin = $this->_auth_admin();

        $result = $this->format->run('module/admin/admin/myprofile', $a_admin);

        return $this->_echo_json(E::SUCCESS, $result);
    }

    public function change_password() {
        $this->load->library('module/admin/authorization');
        if(($auth = $this->_admin_authorization()) !== TRUE) return $this->_echo_json($auth);

        $a_admin = $this->_auth_admin();

        $password_old = $this->input->post('password_old');
        $password_new = $this->input->post('password_new');

        $this->load->model('module/admin/admin_model');
        if($this->admin_admin_model->password_verify($a_admin, $password_old) === FALSE) return $this->_echo_json(E::AUTH_CHANGE_PASSWORD_INVALID_OLD_PASSWORD);

        $a_admin['password_token'] = $this->admin_admin_model->update_password($a_admin['id'], $password_new);

        $this->load->library('module/admin/authorization');
        $result = $this->format->run('module/admin/admin/login_success', $a_admin);

        return $this->_echo_json(E::SUCCESS, $result);
    }

}
