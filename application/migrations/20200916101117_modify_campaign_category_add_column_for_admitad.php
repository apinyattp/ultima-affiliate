<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_category_add_column_for_admitad extends CI_Migration
{
	public function __construct() {
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		$this->campaign_category_add_column_for_admitad_table();
	}

	public function down() {

		$this->dbforge->drop_column('campaign_category', 'id');
		$this->dbforge->drop_column('campaign_category', 'parent_id');

		$fields = array(
			'value' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'comment' => ''
			)
		);
		$this->dbforge->modify_column('campaign_category', $fields);
	}

	private function campaign_category_add_column_for_admitad_table() {
		$fields = array(
			'id' => array(
				'type' => 'INT',
				'null' => FALSE,
				'unsigned' => TRUE,
				'constraint' => 5,
				'first' => TRUE
			),
			'parent_id' => array(
				'type' => 'INT',
				'null' => TRUE,
				'constraint' => 5,
				'comment' => 'Parent category ID',
				'after' => 'item'
			),
		);
		$this->dbforge->add_column('campaign_category', $fields);

		$fields = array(
			'value' => array(
				'type' => 'INT',
				'null' => TRUE,
				'unsigned' => TRUE,
				'comment' => ''
			)
		);
		$this->dbforge->modify_column('campaign_category', $fields);

	}
}

/* End of file 20200916101117_modify_campaign_category_add_column_for_admitad.php */
/* Location: ./application/migrations/20200916101117_modify_campaign_category_add_column_for_admitad.php */
