<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_set_reward extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_set_reward_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_set_reward');
	}

	private function create_campaign_set_reward_table()
	{
		$this->dbforge->add_field(array(
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'new' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => 'Set reward for new users'
			),
			'existing' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2],
				'comment' => 'Set reward for existing users'
			)
		));
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('campaign_id', TRUE);
		$this->dbforge->create_table('campaign_set_reward', TRUE);
	}
}

/* End of file 20200905175042_create_campaign_set_reward.php */
/* Location: ./application/migrations/20200905175042_create_campaign_set_reward.php */
