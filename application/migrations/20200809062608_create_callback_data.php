<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_callback_data extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_callback_data_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('callback_data');
	}

	private function create_callback_data_table()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'data' => array(
				'type' => 'TEXT',
				'null' => TRUE,
			)
		));
		$this->dbforge->add_field('`datetime_created` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->create_table('callback_data', TRUE);
	}
}

/* End of file 20200809062608_create_callback_data.php */
/* Location: ./application/migrations/20200809062608_create_callback_data.php */
