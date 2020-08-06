<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Format
{
    private $_format_paths = [APPPATH.'formats/' => TRUE];
    private $_ci;

    public function __construct() {
        $this->_ci =& get_instance();
    }

    public function __get(string $name) {
        return $this->_ci->$name;
    }

    public function __call(string $name, array $arguments) {
        return call_user_func_array([$this->_ci, $name], $arguments);
    }

    private function _format_file($format) {
        $_ext = pathinfo($format, PATHINFO_EXTENSION);
        $_file = ($_ext === '') ? $format.'.php' : $format;

        $file_exists = FALSE;
        $_file_path = $format;

        $module_loaded = FALSE;
        if(list($module, $path, $class) = $this->_ci->load->detect_module($format)) {
            $this->load->add_module($module);
            $module_loaded = TRUE;
            $_file = $path.$class.'.php';
        }

        foreach ($this->_ci->load->get_package_paths(TRUE) as $package_path) {
            $package_path .= 'formats/'.$_file;
            if (file_exists($package_path)) {
                $_file_path = $package_path;
                $file_exists = TRUE;
                break;
            }
        }

        if($module_loaded) {
            $this->load->remove_module($module, FALSE);
        }

        if ( ! $file_exists && ! file_exists($_file_path)) {
            show_error('Unable to load the requested file: '.$_file_path);
        }

        return $_file_path;
    }

    private function _fetch_data($file_path, $vars=[]) {
        extract($vars);

        return include($file_path);
    }

    public function run($format, $vars=[], $varnames='data') {
        if($varnames !== FALSE) $vars = [$varnames => $vars];
        $file_path = $this->_format_file($format);
        return $this->_fetch_data($file_path, $vars);
    }

    public function map($format, $datas, $varnames='data') {
        $file_path = $this->_format_file($format);
        return array_map(
            function($vars) use ($file_path, $varnames) {
                if($varnames !== FALSE) $vars = [$varnames => $vars];
                return $this->_fetch_data($file_path, $vars);
            },
            $datas
        );
    }

}
