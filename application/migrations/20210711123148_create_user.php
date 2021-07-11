<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_user extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_user_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('user');
	}

	private function create_user_table()
	{
		$this->dbforge->add_field(
			array(
				'id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'auto_increment' => TRUE,
					'unsigned' => TRUE,
					'constraint' => 11
				),
				'uuid' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'real uuid user'
				),
				'jelala_id' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'generate new uuid user for cms'
				),
				'company' => array(
                    'type' => 'varchar',
                    'null' => FALSE,
                    'constraint' => 255,
					'comment' => 'company name'
				)
			)
		);
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
		$this->dbforge->add_key('uuid');
		$this->dbforge->add_key('jelala_id');
		$this->dbforge->add_key('company');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('user', TRUE);
	}
}

/* End of file 20210711123148_create_user.php */
/* Location: ./application/migrations/20210711123148_create_user.php */
