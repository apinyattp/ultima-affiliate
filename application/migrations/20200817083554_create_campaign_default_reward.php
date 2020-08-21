<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_default_reward extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_default_reward_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_default_reward');
	}

	private function create_campaign_default_reward_table()
	{
		$this->dbforge->add_field(array(
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'type' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 20,
				'comment' => ''
			),
			'name' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 255,
				'comment' => ''
			),
			'reward' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'constraint' => [18, 2],
				'comment' => ''
			),
			'customerType' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 20,
				'comment' => ''
			)
		));
		$this->dbforge->add_key('campaign_id');
		$this->dbforge->create_table('campaign_default_reward', TRUE);
	}
}

/* End of file 20200817083554_create_campaign_default_reward.php */
/* Location: ./application/migrations/20200817083554_create_campaign_default_reward.php */
