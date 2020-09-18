<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_report_conversion_column_admitad extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->report_conversion_column_admitad_table();
	}

	public function down()
	{
		$fields = array(
			'status' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'constraint' => [
					'PENDING',
					'APPROVED',
					'REJECTED'
				],
				'comment' => ''
			),
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'Campaign ID'
			),
			'campaign_name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => 'Campaign Name'
			),
			'session_id' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => ''
			),
		);
		$this->dbforge->modify_column('report_conversion', $fields);

		$this->dbforge->drop_column('report_conversion', 'user_agent');
	}

	private function report_conversion_column_admitad_table()
	{

		$fields = array(
			'user_agent' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'after' => 'session_id'
			)
		);
		$this->dbforge->add_column('report_conversion', $fields);

		$fields = array(
			'status' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'constraint' => [
					'PENDING',
					'APPROVED',
					'REJECTED',
					'NEW'
				],
				'comment' => ''
			),
			'creative_id' => array(
				'type' => 'INT',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'comment' => 'ID of banner which user clicked'
			),
			'creative_name' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 255,
				'comment' => 'Name of banner which user clicked'
			),
			'session_id' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 255,
				'comment' => ''
			),
		);
		$this->dbforge->modify_column('report_conversion', $fields);
	}
}

/* End of file 20200917173628_modify_report_conversion_column_admitad.php */
/* Location: ./application/migrations/20200917173628_modify_report_conversion_column_admitad.php */
