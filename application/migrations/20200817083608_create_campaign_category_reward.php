<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_category_reward extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_category_reward_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_category_reward');
	}

	private function create_campaign_category_reward_table()
	{
		$this->dbforge->add_field(array(
			'id' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 20
			),
			'campaign_id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5
			),
			'name' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 255,
				'comment' => ''
			),
			'type' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 20,
				'comment' => ''
			),
			'reward' => array(
				'type' => 'DOUBLE',
				'null' => FALSE,
				'constraint' => [18, 2],
				'comment' => ''
			)
		));
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('id', TRUE);
		$this->dbforge->add_key('campaign_id');
		$this->dbforge->create_table('campaign_category_reward', TRUE);
	}
}

/* End of file 20200817083608_create_campaign_category_reward.php */
/* Location: ./application/migrations/20200817083608_create_campaign_category_reward.php */
