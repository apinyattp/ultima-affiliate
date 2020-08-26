<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_admin_add_role extends CI_Migration
{

    public function __construct() {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up() {
        $this->dbforge->add_column('admin', '`role` VARCHAR(40) NOT NULL DEFAULT "" AFTER `username`');
    }

    public function down() {
        $this->dbforge->drop_column('admin', 'role');
    }

}

/* End of file 20190402031443_modify_admin_add_role.php */
/* Location: ./application/migrations/20190402031443_modify_admin_add_role.php */
