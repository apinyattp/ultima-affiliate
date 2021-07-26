<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_mission_conversion extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_mission_conversion_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('mission_conversion');
	}

	private function create_mission_conversion_table()
	{
		$this->dbforge->add_field(array(
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
				'unsigned' => TRUE,
				'constraint' => 255,
				'comment' => 'uuid'
			),
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 11,
				'comment' => 'campaign_id'
			),
			'order_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 11,
				'comment' => 'order_id'
			),
			'amount' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => 'amount'
			),
			'order_date' => array(
				'type' => 'DATETIME',
				'null' => FALSE,
				'unsigned' => TRUE,
				'comment' => 'order_date'
			)
		));
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key(['uuid', 'campaign_id', 'order_id']);
		$this->dbforge->create_table('missing_conversion', TRUE);
	}
}

/* End of file 20210726054410_create_mission_conversion.php */
/* Location: ./application/migrations/20210726054410_create_mission_conversion.php */
