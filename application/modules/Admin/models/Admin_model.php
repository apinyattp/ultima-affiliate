<?php
namespace Module\Admin\Models;

class Admin_model extends \MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_by_id($admin_id) {
        $this->db->from('admin')
                ->where('id', $admin_id)
                ->limit(1);
        return $this->db->get()->row_array();
    }

    public function get_by_email($email, $admin_id=FALSE) {
        $this->db->from('admin')
                ->where('email', $email)
                ->limit(1);
        if($admin_id) $this->db->where('id !=', $admin_id);
        return $this->db->get()->row_array();
    }

    public function get_by_username($username, $admin_id=FALSE) {
        $this->db->from('admin')
                ->where('username', $username)
                ->limit(1);
        if($admin_id) $this->db->where('id !=', $admin_id);
        return $this->db->get()->row_array();
    }

    public function password_verify($a_admin, $password) {
        return password_verify($password, $a_admin['password']);
    }

    public function login($username, $password) {
        $a_admin = $this->get_by_username($username);

        if(empty($a_admin) || $a_admin['status'] !== 'active') return FALSE;
        if(!$this->password_verify($a_admin, $password)) return FALSE;

        return $a_admin;
    }

    public function forgot_password($admin_id) {
        $token = GUID();
        $a_set = [
            'reset_password_token' => $token,
        ];
        $this->db->update('admin', $a_set, ['id' => $admin_id]);
        return $token;
    }

    public function update_password($admin_id, $password_new) {
        $password_token = GUID();
        $a_set = [
            'password_token' => $password_token,
            'password' => password_hash($password_new, PASSWORD_DEFAULT),
        ];
        $this->db->update('admin', $a_set, ['id' => $admin_id]);
        return $password_token;
    }

    public function update_status($admin_id, $status) {
        $a_set = [
            'status' => $status,
        ];
        $this->db->update('admin', $a_set, ['id' => $admin_id]);
    }

}
