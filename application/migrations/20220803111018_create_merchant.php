<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_merchant extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->create_merchant_table();
    }

    public function down()
    {
        $this->dbforge->drop_table('merchant');
    }

    private function create_merchant_table()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'null' => FALSE,
                'auto_increment' => TRUE,
                'unsigned' => TRUE,
            ),
            'campaign_id' => array(
                'type' => 'INT',
                'null' => FALSE,
                'unsigned' => TRUE,
            ),
            'name' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 255,
            ),
            'url_conversion' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 64,
            ),
            'token' => array(
                'type' => 'VARCHAR',
                'null' => FALSE,
                'constraint' => 255,
                'unique' => TRUE,
            ),
            'is_active' => array(
                'type' => 'BOOLEAN',
                'null' => FALSE,
            ),
        ));
        $this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('merchant', TRUE);
    }
}

/* End of file 20220803111018_create_merchant.php */
/* Location: ./application/migrations/20220803111018_create_merchant.php */
