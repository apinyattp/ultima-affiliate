<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_add_column_coming_soon extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->campaign_add_column_coming_soon_table();
	}

	public function down() {
		$this->dbforge->drop_column('campaign', 'coming_soon');
	}

	private function campaign_add_column_coming_soon_table() {
		$fields = array(
            'coming_soon' => array(
				'type' => 'TINYINT',
				'null' => FALSE,
                'default' => 0,
				'after' => 'note',
				'comment' => 'Flag coming soon'
			)
        );
		$this->dbforge->add_column('campaign', $fields);
	}
}

/* End of file 20200901081030_modify_campaign_add_column_coming_soon.php */
/* Location: ./application/migrations/20200901081030_modify_campaign_add_column_coming_soon.php */
