<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_custom_reward extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_custom_reward_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_custom_reward');
	}

	private function create_campaign_custom_reward_table()
	{
		$this->dbforge->add_field(array(
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'type' => array(
				'type' => 'ENUM',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => ['default', 'category', 'customer_type']
			),
			'tier1' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2]
			),
			'tier2' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2]
			),
			'tier3' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2]
			),
			'tier4' => array(
				'type' => 'DOUBLE',
				'null' => TRUE,
				'unsigned' => TRUE,
				'constraint' => [18, 2]
			),
		));
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('campaign_id', TRUE);
		$this->dbforge->add_key('type', TRUE);
		$this->dbforge->create_table('campaign_custom_reward', TRUE);
	}
}

/* End of file 20200819161622_create_campaign_custom_reward.php */
/* Location: ./application/migrations/20200819161622_create_campaign_custom_reward.php */
