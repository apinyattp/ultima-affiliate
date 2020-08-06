<?php
/**
 * @author Nutdanai Tippanontakul
 */
namespace Builder\Reader;

class Reader {
    private $_builder;
    private $_path;
    private $_readed_paths = [];
    private $_a_data = [
        'api' => [],
        'sitemap' => [],
    ];
    private $_a_endpoint = [];

    public function __construct($builder) {
        $this->set_builder($builder);
    }

    public function set_builder($builder) {
        $this->_builder = $builder;
    }

    public function set_path($path) {
        $this->_path = $path;
    }

    public function read($path, $module_name=FALSE) {
        if(!empty($path)) $this->set_path($path);

        if(in_array($this->_path, $this->_readed_paths)) return;

        $this->_read_dir($this->_path, $module_name);
        $this->_readed_paths[] = $this->_path;
    }

    public function _read_dir($path, $module_name=FALSE) {
        $handler = opendir($path);
        while (($file = readdir($handler)) !== FALSE) {
            if(in_array($file, ['.', '..', 'index.html'])) continue;
            $file_path = $path . DIRECTORY_SEPARATOR . $file;
            if(is_dir($file_path)) {
                $this->_read_dir($file_path, $module_name);
            }else{
                $this->_read_file($file_path, $module_name);
            }
        }
        closedir($handler);
    }

    public function _read_file($file_path, $module_name=FALSE) {
        $type = $this->_type();
        $name = str_replace($this->_path, "", $file_path);
        $handler = fopen($file_path, 'r');
        if(!$handler) throw new \Exception("Can not open file {$file_path}");

        $line_reader = new LineReader($this, $name, $type, $module_name);

        while($line = fgets($handler)) {
            $line_reader->read($line);
        }

        $this->_a_data[$type][$name] = $line_reader->get_data();
    }

    public function add_endpoint($method, $endpoint_path, $endpoint) {
        if(isset($this->_a_endpoint[$endpoint_path][$method])) throw new \Exception("Duplicate endpoint {$method}:{$endpoint_path}");
        $this->_a_endpoint[$endpoint_path][$method] = $endpoint;
        if($method === 'GET_POST') {
            $this->add_endpoint('GET', $endpoint_path, $endpoint);
            $this->add_endpoint('POST', $endpoint_path, $endpoint);
        }
    }

    public function get_data() {
        return $this->_a_data;
    }

    public function get_endpoint() {
        return $this->_a_endpoint;
    }

    protected function _type() {
        if(preg_match('/api\-sitemap\/$/', $this->_path)) return 'sitemap';
        return 'api';
    }

    public function cache_load($a_cache) {
        foreach($a_cache as $cache) {
            $type = $cache['type'];
            $name = $cache['name'];
            $data = $cache['data'];

            $_data = new Data();
            $_data->cache_load($this, $data);
            $this->_a_data[$type][$name] = $_data;
        }
    }

    public function cache_save() {
        $a_cache = [];
        foreach($this->_a_data as $type => $a_data) {
            foreach ($a_data as $name => $_data) {
                $a_cache[] = [
                    'type' => $type,
                    'name' => $name,
                    'data' => $_data->cache_save(),
                ];
            }
        }
        return $a_cache;
    }

}
