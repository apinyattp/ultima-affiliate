<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_data_column extends CI_Migration {

	public function __construct() {
		$this->load->dbforge();
		$this->load->database();
	}

	public function up() {
		$this->campaign_data_column_table();
	}

	public function down() {
		$fields = array(
			'type' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 10,
				'comment' => 'Campaign Type'
			),
			'selfConversion' => array(
				'type' => 'VARCHAR',
				'null' => FALSE,
				'constraint' => 20,
				'comment' => 'Self Conversion'
			),
			'pointBack' => array(
				'type' => 'TINYINT',
				'null' => FALSE,
				'default' => 0,
				'comment' => 'Is point back'
			),
			'englishDescription' => array(
				'type' => 'TEXT',
				'null' => FALSE,
				'comment' => 'Campaign English description'
			),
			'customCreativesAvailable' => array(
				'type' => 'TINYINT',
				'null' => FALSE,
				'default' => 0,
				'comment' => 'Is Customer creatives available'

			),
			'seoContentAvailable' => array(
				'type' => 'TINYINT',
				'null' => FALSE,
				'default' => 0,
				'comment' => 'Is seo content available'

			),
			'productFeedAvailable' => array(
				'type' => 'TINYINT',
				'null' => FALSE,
				'default' => 0,
				'comment' => 'Is product feeed available'
			),
			'affiliatedDate' => array(
				'type' => 'TEXT',
				'null' => FALSE
			),
		);
		$this->dbforge->modify_column('campaign_data', $fields);
	}

	private function campaign_data_column_table() {
		$fields = array(
			'type' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 10,
				'comment' => 'Campaign Type'
			),
			'selfConversion' => array(
				'type' => 'VARCHAR',
				'null' => TRUE,
				'constraint' => 20,
				'comment' => 'Self Conversion'
			),
			'pointBack' => array(
				'type' => 'TINYINT',
				'null' => TRUE,
				'default' => 0,
				'comment' => 'Is point back'
			),
			'englishDescription' => array(
				'type' => 'TEXT',
				'null' => TRUE,
				'comment' => 'Campaign English description'
			),
			'customCreativesAvailable' => array(
				'type' => 'TINYINT',
				'null' => TRUE,
				'default' => 0,
				'comment' => 'Is Customer creatives available'
			),
			'seoContentAvailable' => array(
				'type' => 'TINYINT',
				'null' => TRUE,
				'default' => 0,
				'comment' => 'Is seo content available'
			),
			'productFeedAvailable' => array(
				'type' => 'TINYINT',
				'null' => TRUE,
				'default' => 0,
				'comment' => 'Is product feeed available'
			),
			'affiliatedDate' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
		);
		$this->dbforge->modify_column('campaign_data', $fields);
	}
}

/* End of file 20200916180311_modify_campaign_data_column.php */
/* Location: ./application/migrations/20200916180311_modify_campaign_data_column.php */
