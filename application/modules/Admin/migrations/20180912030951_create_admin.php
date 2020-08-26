<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_admin extends CI_Migration
{

    public function __construct() {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up() {
        $this->create_admin_table();
    }

    public function down() {
        $this->dbforge->drop_table('admin');
    }

    private function create_admin_table() {
        $this->dbforge->add_field(
            array(
                'id' => array(
                    'type' => 'INT',
                    'null' => FALSE,
                    'auto_increment' => TRUE,
                    'unsigned' => TRUE,
                ),
                'username' => array(
                    'type' => 'VARCHAR',
                    'null' => FALSE,
                    'unique' => TRUE,
                    'constraint' => 255
                ),
                'email' => array(
                    'type' => 'VARCHAR',
                    'null' => TRUE,
                    'unique' => TRUE,
                    'constraint' => 255
                ),
                'status' => array(
                    'type' => 'ENUM',
                    'constraint' => array(
                        'active',
                        'inactive',
                    )
                ),
                'password' => array(
                    'type' => 'VARCHAR',
                    'null' => FALSE,
                    'constraint' => 255
                ),
                'password_token' => array(
                    'type' => 'VARCHAR',
                    'null' => FALSE,
                    'constraint' => 64
                ),
                'reset_password_token' => array(
                    'type' => 'VARCHAR',
                    'null' => TRUE,
                    'constraint' => 64
                ),
            )
        );
        $this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('admin', TRUE);

        $admin = array(
            array(
                'id' => '1',
                'username' => 'admin',
                'email' => '',
                'status' => 'active',
                'password' => '$2y$10$Gp8bOrBPr3F1JI1mPG5ri.JBopFJPqpJle7Pi63rxeaajDvkxKMMW',
                'password_token' => '90EC24C9-0E21-48AC-81EC-B0EC0C358BFB'
            ),
        );
        $this->db->insert_batch('admin', $admin);
    }

}

/* End of file 20180912030951_create_admin.php */
/* Location: ./application/migrations/20180912030951_create_admin.php */
