<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Core;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Router extends \CI_Router {

    private $_module_name = NULL;

    public function __construct() {
        parent::__construct();
        if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') exit;
    }

    public function set_module($module_name) {
        $this->_module_name = $module_name;
    }

    protected function _parse_routes() {
        $a_segment = $this->uri->segment_array();

        if($a_segment[1] == 'api') {
            $uri = implode('/', $this->uri->segment_array());

            $builder = & \Builder\Builder::get_instance();
            $builder->read();
            list($uri_new, $module) = $builder->router($uri);

            if(!empty($module)) $this->directory = '../modules/'.$module.'/controllers/';

            if($uri_new !== FALSE && $uri_new !== $uri) {
                return $this->_set_request(explode('/', $uri_new));
            }
        }

        if($a_segment[1] == 'document') {
            $uri = implode('/', $this->uri->segment_array());

            $this->directory = "../libraries/Builder/Document/";

            $goto = ['render', 'index'];
            if(isset($a_segment[2]) && in_array($a_segment[2], ['auth'])) {
                $goto = ['render', $a_segment[2]];
            }
            return $this->_set_request(array_merge($goto, $this->uri->segment_array()));
        }

        if($a_segment[1] == 'unittest') {
            $uri = implode('/', $this->uri->segment_array());

            $this->directory = "../libraries/Builder/Unittest/";

            return $this->_set_request(array_merge(['ui', 'index'], $this->uri->segment_array()));
        }

        parent::_parse_routes();
    }

    protected function _validate_request($segments) {
        $c = count($segments);

        // Loop through our segments and return as soon as a controller
        // is found or when such a directory doesn't exist
        while ($c-- > 0)
        {
            $test = $this->directory
                .ucfirst($this->translate_uri_dashes === TRUE ? str_replace('-', '_', $segments[0]) : $segments[0]);

            if ( ! file_exists(APPPATH.'controllers/'.$test.'.php')
                && is_dir(APPPATH.'controllers/'.$this->directory.$segments[0])
            )
            {
                $this->set_directory(array_shift($segments), TRUE);
                continue;
            }

            return $segments;
        }

        // This means that all segments were actually directories
        return $segments;
    }

}
