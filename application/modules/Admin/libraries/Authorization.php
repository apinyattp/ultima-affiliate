<?php
namespace Module\admin\Libraries;

class Authorization {

    private $_ci;
    private $_login_admin;
    private $_a_config;

    public function __construct() {
        $this->_ci =& get_instance();
    }

    public function authorization_check($options=FALSE) {
        $token = $this->authorization_token();

        $this->_ci->load->helper('jwt');
        $payload = jwt_decode($token);
        print_r($token);
        print_r($payload);die();
        // FAIL TO DECODE TOKEN
        if($payload === FALSE) return \E::INVALID_FORMAT_TOKEN;

        $admin_id = isset($payload['uid']) ? $payload['uid'] : NULL;
        $password_token = isset($payload['ptoken']) ? $payload['ptoken'] : NULL;
        $expire = isset($payload['exp']) ? $payload['exp'] : NULL;

        // CHECK admin VAR
        if(empty($admin_id)) return \E::INVALID_FORMAT_TOKEN;
        if(empty($password_token)) return \E::INVALID_FORMAT_TOKEN;
        if(empty($expire)) return \E::INVALID_FORMAT_TOKEN;

        // CHECK EXPIRE
        if(isset($this->_a_config['check_expire']) && $this->_a_config['check_expire'] && $expire < time()){
            return \E::PERMISSION_TOKEN_EXPIRE;
        }

        $this->_ci->load->model('module/admin/admin_model');
        $a_admin = $this->_ci->admin_admin_model->get_by_id($admin_id);

        // CHECK admin DATA
        if($a_admin['password_token'] !== $password_token) return \E::PERMISSION_TOKEN_EXPIRE;

        // CHECK MODULE LOADED
        $admin_role = (bool) $this->_ci->load->find_module('admin_role');
        $admin_subrole = (bool) $this->_ci->load->find_module('admin_subrole');
        $admin_permission = (bool) $this->_ci->load->find_module('admin_permission');

        $role_check = FALSE;
        $permission_check = FALSE;
        if($admin_role === TRUE && is_array($options) && isset($options['role'])) {
            $role_check = $options['role'];
        }
        if($admin_permission === TRUE && is_array($options) && isset($options['permission'])) {
            $permission_check = $options['permission'];
        }

        if($admin_role !== FALSE && $admin_permission !== FALSE){
            // ROLE AND PERMISSION
        }else if($admin_role !== FALSE && $role_check === FALSE) {
            // ROLE ONLY
            $args = func_get_args();
            if(!empty($args)){
                $role_check = [];
                if($admin_subrole === TRUE) {
                    for($i = 0; $i < count($args); $i += 2) {
                        $role = $args[$i];
                        if(!is_string($role) && !is_numeric($role)) throw new Exception('role is not string.');
                        $subrole = isset($args[$i + 1]) ? $args[$i + 1] : FALSE;
                        if($subrole !== FALSE && !is_array($subrole)) $subrole = [$subrole];
                        $role_check[$role] = $subrole;
                    }
                }else if(is_array($options)){
                    $role_check = $options;
                }else{
                    $role_check = $args;
                }
            }
        }else if($admin_permission !== FALSE && $permission_check === FALSE) {
            // PERMISSION ONLY
            $args = func_get_args();
            if(!empty($args)){
                $permission_check = [];
                for($i = 0; $i < count($args); $i += 2) {
                    $scope = $args[$i];
                    $a_action = isset($args[$i + 1]) ? $args[$i + 1] : FALSE;
                    $permission_check[$scope] = $a_action;
                }
            }
        }

        // VERIFY ROLE
        if($role_check !== FALSE) {
            if(!is_array($role_check)) $role_check = [$role_check];
            if($admin_subrole) {
                $_role_check_role = array_keys($role_check);
            }else{
                $_role_check_role = $role_check;
            }

            if(!in_array($a_admin['role'], $_role_check_role)) return \E::PERMISSION_DENIED;

            if($admin_subrole && $role_check[$a_admin['role']]) {
                $_role_check_subrole = $role_check[$a_admin['role']];
                if(!in_array($a_admin['subrole'], $_role_check_subrole)) return \E::PERMISSION_DENIED;
            }
        }

        // VERIFY PERMISSON
        if($permission_check !== FALSE) {
            $admin_permission = json_decode($a_admin['permission'], TRUE);
            $permission = FALSE;
            foreach ($permission_check as $scope => $a_action) {
                if(!array_key_exists($scope, $a_admin['permission'])) continue;

                if($a_action !== FALSE) {
                    if(!is_array($a_action)) $a_action = [$a_action];
                    foreach ($a_action as $action) {
                        if(!empty($admin_permission[$scope][$action])) {
                            $permission = TRUE;
                            break 2;
                        }
                    }
                }else{
                    foreach ($admin_permission[$scope] as $_permission) {
                        if(!empty($_permission)) {
                            $permission = TRUE;
                            break 2;
                        }
                    }
                }
            }
            if($permission === FALSE) return \E::PERMISSION_DENIED;
        }

        $this->_login_admin = $a_admin;

        return TRUE;
    }

    public function get_login_admin() {
        return $this->_login_admin;
    }

    public function authorization_token() {
        if(isset($_COOKIE["utoken"])){
            return $_COOKIE["utoken"];
        }else {
            return $this->http_authorization_token();
        }
        return FALSE;
    }

    public function http_authorization_token() {
        if(isset($_SERVER['HTTP_Authorization'])) return $_SERVER['HTTP_Authorization'];
        if(isset($_SERVER['HTTP_AUTHORIZATION'])) return $_SERVER['HTTP_AUTHORIZATION'];

        if(function_exists('apache_request_headers')){
            $a_header = apache_request_headers();
            if(isset($a_header['Authorization'])) return $a_header['Authorization'];
        }
        if($this->_ci->input->json('Authorization')) return $this->_ci->input->json('Authorization');
        if($this->_ci->input->get('Authorization')) return $this->_ci->input->get('Authorization');

        return FALSE;
    }

    public function generate_token($admin_id, $password_token, $data, $expire_day=10) {
        $payload = [
            'uid' => $admin_id,
            'ptoken' => $password_token,
            'data' => $data,
            'exp' => time() + ($expire_day * 24 * 60 * 60),
        ];

        $this->_ci->load->helper('jwt');

        return jwt_encode($payload);
    }

    public function set_config($a_config) {
        $this->_a_config = $a_config;
    }

}
