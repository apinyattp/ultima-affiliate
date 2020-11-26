<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_category_reward_add_column_text extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->campaign_category_reward_add_column_text_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('campaign_category_reward', 'text');
	}

	private function campaign_category_reward_add_column_text_table()
	{
		$fields = array(
			'text' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => 'category reward text desc',
				'after' => 'type'
			),
		);

		$this->dbforge->add_column('campaign_category_reward', $fields);
	}
}

/* End of file 20201125040301_modify_campaign_category_reward_add_column_text.php */
/* Location: ./application/migrations/20201125040301_modify_campaign_category_reward_add_column_text.php */
