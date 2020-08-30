<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_file extends CI_Migration
{

    public function __construct() {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up() {
        $this->create_file_table();
    }

    public function down() {
        $this->dbforge->drop_table('file');
    }

    private function create_file_table() {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'INT',
                    'null' => FALSE,
                    'auto_increment' => TRUE,
                    'unsigned' => TRUE,
                ),
                'user_id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'null' => TRUE,
                ),
                'content_id' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                    'null' => TRUE,
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => ['temp', 'live', 'deleted'],
                ),
                'endpoint' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ),
                'file_type' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                ),
                'file_size' => array(
                    'type' => 'INT',
                    'unsigned' => TRUE,
                ),
                'file_extension' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 5,
                ),
                'file_path' => array(
                    'type' => 'VARCHAR',
                    'constraint' => 255,
                ),
                'file_data' => array(
                    'type' => 'TEXT',
                ),
            )
        );
        $this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->add_key('content_id', FALSE);
        $this->dbforge->add_key('status', FALSE);
        $this->dbforge->add_key('user_id', FALSE);
        $this->dbforge->create_table('file', TRUE);
    }

}

/* End of file 20180912030953_create_file.php */
/* Location: ./application/migrations/20180912030953_create_file.php */
