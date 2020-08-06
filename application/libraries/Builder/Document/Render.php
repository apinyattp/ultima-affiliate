<?php
/**
 * @author Nutdanai Tippanontakul
 */

class Render extends \Builder\Core\Controller {

    public function __construct () {
        parent::__construct();
        $this->__init_package();

        $this->load->library('doc_head');
        $this->load->library('doc_auth');
    }

    private function __init_package() {
        $doc_path = dirname(__FILE__).DIRECTORY_SEPARATOR;
        $this->load->add_package_path($doc_path);
    }

    public function index() {
        if(($auth = $this->doc_auth->verify_token()) !== TRUE) {
            redirect($this->doc_auth->webgen_redirect_uri());
            return;
        }

        $endpoint = $this->uri->uri_string();

        $this->load->library("api");
        $this->api->document($endpoint);
    }

    public function auth() {
        if(($auth = $this->doc_auth->verify_token()) === TRUE) {
            return redirect('document');
        }

        $token = $this->input->get('token');
        if($token && ($auth = $this->doc_auth->verify_token($token)) === TRUE){
            return redirect('document');
        }

        redirect($this->doc_auth->webgen_redirect_uri());
    }

}
