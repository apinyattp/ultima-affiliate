<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Core;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Loader extends \CI_Loader {

    protected $ci_modules = array();

    public function __construct() {
        parent::__construct();
    }

    public function set_view_paths($path) {
        $this->_ci_view_paths = array($path => TRUE);
    }

    public function config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE) {
        if (list($module, $path, $class) = $this->detect_module($file)) {

            if (in_array($module, $this->ci_modules)) {
                return parent::config($class, $use_sections, $fail_gracefully);
            }

            $this->add_module($module);

            $void = parent::config($path.$class, $use_sections, $fail_gracefully);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            parent::config($file, $use_sections, $fail_gracefully);
        }
    }

    public function model($model, $name = '', $db_conn = FALSE) {
        if (empty($model)) {
            return $this;
        }elseif (is_array($model)) {
            foreach ($model as $key => $value) {
                is_int($key) ? $this->model($value, '', $db_conn) : $this->model($key, $value, $db_conn);
            }

            return $this;
        }

        // Detect module
        if (list($module, $path, $class) = $this->detect_module($model)) {
            $this->_ci_model();

            $model = $this->_require_file($module, 'models', $path, $class);

            if($name == '') {
                $name = $module.'_'.$class;
            }
        }
        return parent::model($model, $name, $db_conn);
    }

    public function view($view, $vars = array(), $return = FALSE) {
        if (list($module, $path, $file) = $this->detect_module($view)) {
            if (in_array($module, $this->ci_modules)) {
                return parent::view($path.$file, $vars, $return);
            }

            $this->add_module($module);

            $void = parent::view($path.$file, $vars, $return);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return parent::view($view, $vars, $return);
        }
    }

    public function helper($helper = array()) {
        if (is_array($helper)) {
            foreach ($helper as $_helper) {
                $this->helper($_helper);
            }
            return $this;
        }

        if (list($module, $path, $file) = $this->detect_module($helper)) {

            if (in_array($module, $this->ci_modules)) {
                return parent::helper($path.$file);
            }

            $this->add_module($module);

            $void = parent::helper($path.$file);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return parent::helper($helper);
        }
    }

    public function language($file = array(), $lang = '') {
        if (empty($file)) {
            return $this;
        }elseif (is_array($file)) {
            foreach ($file as $langfile) {
                $this->language($langfile, $lang);
            }
            return;
        }

        if (list($module, $path, $file_lang) = $this->detect_module($file)) {
            if (in_array($module, $this->ci_modules)) {
                return parent::language($path.$file_lang, $lang);
            }

            $this->add_module($module);

            $void = parent::language($path.$file_lang, $lang);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return parent::language($file, $lang);
        }
    }

    public function format($format, $vars=[], $varnames='data') {
        if (list($module, $path, $file) = $this->detect_module($format)) {
            if (in_array($module, $this->ci_modules)) {
                return $this->_format($path.$file, $vars, $varnames);
            }

            $this->add_module($module);

            $void = $this->_format($path.$file, $vars, $varnames);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return $this->_format($format, $vars, $varnames);
        }
    }

    protected function _format($format, $vars=[], $varnames='data') {
        $this->load->library('format');
        return get_instance()->format->run($format, $vars, $varnames);
    }

    public function format_map($format, $datas, $varnames='data') {
        if (list($module, $path, $file) = $this->detect_module($format)) {
            if (in_array($module, $this->ci_modules)) {
                return $this->_format_map($path.$file, $datas, $varnames);
            }

            $this->add_module($module);

            $void = $this->_format_map($path.$file, $datas, $varnames);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return $this->_format_map($format, $datas, $varnames);
        }
    }

    protected function _format_map($format, $datas, $varnames='data') {
        $this->load->library('format');
        return get_instance()->format->map($format, $datas, $varnames);
    }

    public function json($json) {
        if (list($module, $path, $file) = $this->detect_module($json)) {
            if (in_array($module, $this->ci_modules)) {
                return $this->_json($path.$file);
            }

            $this->add_module($module);

            $void = $this->_json($path.$file);

            $this->remove_module($module, FALSE);

            return $void;
        } else {
            return $this->_json($json);
        }
    }

    public function _json($json) {
        $a_json = json_decode($json, TRUE);
        if(!empty($a_json)) return $a_json;

        $file_existed = FALSE;

        foreach ($this->get_package_paths(TRUE) as $package_path) {
            $file_path = $package_path.'json/'.$json.'.json';
            if(!file_exists($file_path)) continue;
            $content = trim(file_get_contents($file_path));
            return json_decode($content, TRUE);
        }

        throw new \Exception("Api : Invalid json `$json`");
    }

    protected function _ci_load_library($library, $params = NULL, $object_name = NULL) {
        if (list($module, $path, $class) = $this->detect_module($library)) {
            $library = $this->_require_file($module, 'libraries', $path, $class);

            if($object_name == '') {
                $object_name = $module.'_'.$class;
            }
        }
        return parent::_ci_load_library($library, $params, $object_name);
    }

    private function _require_file($module, $type, $path, $class) {
        $class = ucfirst($class);

        $class_namespace = $this->_class_name($module, $type, $path, $class);
        if ( ! class_exists($class_namespace, FALSE)) {
            $module_path = $this->find_module($module);
            $class_file = $module_path.$type.'/'.$path.$class.'.php';

            if ( ! file_exists($class_file)) {
                throw new \RuntimeException('Unable to locate the '.$this->_single_type($type).' you have specified: '.$class_namespace);
            }

            require_once($class_file);
            if ( ! class_exists($class_namespace, FALSE))
            {
                throw new \RuntimeException($class_file." exists, but doesn't declare class ".$class_namespace);
            }
        }

        return $class_namespace;
    }

    private function _ci_model() {
        if ( class_exists('CI_Model', FALSE)) return;

        $app_path = APPPATH.'core'.DIRECTORY_SEPARATOR;
        if (file_exists($app_path.'Model.php'))
        {
            require_once($app_path.'Model.php');
            if ( ! class_exists('CI_Model', FALSE)) {
                throw new \RuntimeException($app_path."Model.php exists, but doesn't declare class CI_Model");
            }
        }elseif ( ! class_exists('CI_Model', FALSE)){
            require_once(BASEPATH.'core'.DIRECTORY_SEPARATOR.'Model.php');
        }

        $class = config_item('subclass_prefix').'Model';
        if (file_exists($app_path.$class.'.php')) {
            require_once($app_path.$class.'.php');
            if ( ! class_exists($class, FALSE)) {
                throw new \RuntimeException($app_path.$class.".php exists, but doesn't declare class ".$class);
            }
        }
    }

    public function module_list() {
        $a_module = [];
        $handler = opendir(MODULEPATH);
        while (($module_name = readdir($handler)) !== FALSE) {
            if(in_array($module_name, ['.', '..', 'index.html'])) continue;
            $a_module[] = $module_name;
        }
        closedir($handler);
        return $a_module;
    }

    public function detect_module($file) {
        $file = str_replace('.php', '', trim($file, '/'));

        if(($a_file = $this->_segments($file)) === FALSE) return FALSE;
        list($module, $path, $class) = $a_file;

        if(!$this->find_module($module)) return FALSE;

        return $a_file;
    }

    public function find_module($module) {
        $location = MODULEPATH;
        $path = $location . rtrim(ucfirst($module), '/') . DIRECTORY_SEPARATOR;
        if (is_dir($path)) {
            return $path;
        }

        return FALSE;
    }

    public function add_module($module, $view_cascade = TRUE) {
        if ($path = $this->find_module($module)) {
            array_unshift($this->ci_modules, $module);
            $this->add_package_path($path, $view_cascade, TRUE);
        }
    }

    public function remove_module($module = '', $remove_config = TRUE) {
        if ($module == '') {
            array_shift($this->ci_modules);
            parent::remove_package_path('', $remove_config);
        } else if (($key = array_search($module, $this->ci_modules)) !== FALSE) {
            if ($path = $this->find_module($module)) {
                unset($this->ci_modules[$key]);
                parent::remove_package_path($path, $remove_config);
            }
        }
    }

    private function _segments($class) {
        if(class_exists($class)) return FALSE;
        $a_class = explode('/', $class, 3);
        if(count($a_class) < 3) return FALSE;
        list($scope, $module, $class) = $a_class;
        if($scope !== 'module') return FALSE;
        list($path, $class) = $this->_fetch_path($class);
        return array(
            $module,
            $path,
            $class,
        );
    }

    private function _fetch_path($class) {
        $path = '';
        if (($last_slash = strrpos($class, '/')) !== FALSE){
            // The path is in front of the last slash
            $path = substr($class, 0, ++$last_slash);

            // And the model name behind it
            $class = substr($class, $last_slash);
        }
        return array($path, $class);
    }

    private function _class_name($module, $type, $path, $class) {
        $a_namespace = array();
        $a_namespace[] = '\\Module';
        $a_namespace[] = ucfirst($module);
        $a_namespace[] = ucfirst($type);
        foreach (explode('/', $path) as $_path) {
            if(empty($_path)) continue;
            $a_namespace[] = ucfirst($_path);
        }
        $a_namespace[] = ucfirst($class);
        return implode('\\', $a_namespace);
    }

    private function _single_type($type) {
        $a_dic = array(
            'libraries' => 'library',
            'models' => 'model',
        );

        if(isset($a_dic[$type])) return $a_dic[$type];

        return $type;
    }

    public function add_package_path($path, $view_cascade = TRUE, $append=FALSE) {
        $path = rtrim($path, '/').'/';

        if($append){
            array_push($this->_ci_library_paths, $path);
            array_push($this->_ci_model_paths, $path);
            array_push($this->_ci_helper_paths, $path);

            $this->_ci_view_paths = $this->_ci_view_paths + array($path.'views/' => $view_cascade);
        }else{
            array_unshift($this->_ci_library_paths, $path);
            array_unshift($this->_ci_model_paths, $path);
            array_unshift($this->_ci_helper_paths, $path);

            $this->_ci_view_paths = array($path.'views/' => $view_cascade) + $this->_ci_view_paths;
        }

        // Add config file path
        $config =& $this->_ci_get_component('config');
        $config->_config_paths[] = $path;

        return $this;
    }

}
