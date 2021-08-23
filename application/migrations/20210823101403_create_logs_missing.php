<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_logs_missing extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_status_missing_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('missing_conversion', 'status_reject');
	}

	private function create_status_missing_table()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'auto_increment' => TRUE,
				'unsigned' => TRUE,
				'constraint' => 11
			),
			'missing_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 11,
				'comment' => 'report conversion id / missing_id'
			)
		));
                $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(['missing_id']);
		$this->dbforge->create_table('logs_missing', TRUE);
	}
}

/* End of file 20210823101403_create_logs_missing.php */
/* Location: ./application/migrations/20210823101403_create_logs_missing.php */
