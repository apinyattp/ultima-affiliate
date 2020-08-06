<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Reader;

class LineReader {
    private $_data;
    private $_file;
    private $_type;
    private $_module_name = NULL;
    private $_line_count = 0;
    private $_reader;

    public function __construct($reader, $file, $type, $module_name) {
        $this->_reader = $reader;
        $this->_file = $file;
        $this->_type = $type;
        $this->set_module($module_name);
        $this->_init_data();
    }

    public function set_module($module_name) {
        $this->_module_name = $module_name;
    }

    public function _init_data() {
        $this->_data = new Data($this->_module_name);
    }

    public function reset() {
        $this->_init_data();
        $this->_line_count = 0;
    }

    public function get_data() {
        return $this->_data;
    }

    public function read($line) {
        $this->_line_count += 1;
        $line = str_replace("\n", "", $line);
        if(preg_match("/^\/\//", $line)) return;

        if(preg_match("/^===/", $line)) {
            return $this->_data->add_hr();
        }

        if(preg_match("/^(GET|POST|PUT|GET_POST|PAGE|HEADER|INPUT|ERROR|RESULT|JSON):(.*)$/", $line, $match)) {
            $func = $match[1];
            $value = $match[2];
            switch ($func) {
                case 'GET':
                case 'POST':
                case 'PUT':
                case 'GET_POST':
                    if($this->_type == 'api') {
                        $this->_read_endpoint($func, $value, $line);
                    }else{
                        $this->_read_sitemap($func, $value, $line);
                    }
                    break;
                case 'PAGE':
                    $this->_read_page($func, $value, $line);
                    break;
                case 'INPUT':
                    $this->_read_input($func, $value, $line);
                    break;
                case 'HEADER':
                    $this->_read_header($func, $value, $line);
                    break;
                case 'ERROR':
                    $this->_read_error($func, $value, $line);
                    break;
                case 'RESULT':
                    $this->_read_result($func, $value, $line);
                    break;
                case 'JSON':
                    $this->_read_json($func, $value, $line);
                    break;
                default:
                    break;
            }
        }else{
            $this->_data->add_textline($line);
        }
    }

    protected function _read_endpoint($func, $value, $line) {
        if(empty($value)) throw new \Exception("Endpoint is empty: line {$this->_line_count}");

        $value = $this->_api_path($value);

        if(!preg_match('/^api\//', $value)) $value = 'api/'.$value;

        $endpoint = $this->_data->add_endpoint($func, $value, $this->_file);
        $this->_reader->add_endpoint($func, $value, $endpoint);
    }

    protected function _read_sitemap($func, $value, $line) {
        if(empty($value)) throw new \Exception("Endpoint is empty: line {$this->_line_count}");

        $value = $this->_api_path($value);

        $endpoint = $this->_data->add_sitemap($func, $value);
    }

    protected function _read_page($func, $value, $line) {
        if(empty($value)) throw new \Exception("Page is empty: line {$this->_line_count}");
        $endpoint = $this->_data->add_page($value);
    }

    protected function _read_input($func, $value, $line) {
        if(!preg_match("/^([^,]+),([^,]+)(,(.*))?$/", $value, $match)) throw new \Exception("Input Invalid format: {$line}");
        $var_name = $match[1];
        $validate = $match[2];
        $description = isset($match[4]) ? $match[4] : "";
        $this->_data->add_input($var_name, $validate, $description);
    }

    protected function _read_header($func, $value, $line) {
        if(!preg_match("/^([^,]+)(,(.*))?$/", $value, $match)) throw new \Exception("Header Invalid format: {$line}");
        $header = $match[1];
        $description = isset($match[3]) ? $match[3] : "";
        $this->_data->add_header($header, $description);
    }

    protected function _read_error($func, $value, $line) {
        if(!preg_match("/^[0-9]+$/", $value)) {
            if(!defined('E::'.$value)) throw new \Exception("Error not found: {$value}");
            $value = constant('E::'.$value);
        }
        $this->_data->add_error_code($value);
    }

    protected function _read_result($func, $value, $line) {
        $this->_data->add_result($value);
    }

    protected function _read_json($func, $value, $line) {
        $this->_data->add_json($value);
    }

    protected function _api_path($value) {
        return implode(
            "/",
            array_reduce(
                explode("/", $value),
                function($a_segment, $segment) {
                    $segment = trim($segment);
                    if(!empty($segment)) $a_segment[] = $segment;
                    return $a_segment;
                },
                []
            )
        );
    }

}
