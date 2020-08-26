<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Form extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {

        $this->load->helper(array('form', 'url'));

        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required',
                array('required' => 'You must provide a %s.')
        );
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');

        if ($this->form_validation->run() == FALSE)
        {
                $this->load->view('myform');
        }
        else
        {
                $this->load->view('formsuccess');
        }
    }
}
