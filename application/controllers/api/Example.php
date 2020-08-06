<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Example extends MY_Controller {

    public function __construct () {
        parent::__construct();
    }

    public function welcome() {
        $input1 = $this->input->get('input1');
        $input2 = $this->input->get('input2');
        $input3 = $this->input->get('input3');

        switch ($input3) {
            case '+':
                $result = $input1 + $input2;
                break;
            case '-':
                $result = $input1 - $input2;
                break;
        }

        $this->_echo_json(E::SUCCESS, $result);
    }

}
