<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Create_campaign_data extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->create_campaign_data_table();
	}

	public function down()
	{
		$this->dbforge->drop_table('campaign_data');
	}

	private function create_campaign_data_table()
	{
		$this->dbforge->add_field(
			array(
				'campaign_id' => array(
					'type' => 'INT',
					'null' => FALSE,
					'unsigned' => TRUE,
					'constraint' => 5
				),
				'name' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Campaign name'
				),
				'source' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Source of affiliate campaign'
				),
				'url' => array(
					'type' => 'TEXT',
					'null' => FALSE,
					'comment' => 'Campaign URL'
				),
				'quicklink' => array(
					'type' => 'TEXT',
					'null' => TRUE,
					'comment' => 'Campaign Quicklink'
				),
				'type' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 10,
					'comment' => 'Campaign Type'
				),
				'startDate' => array(
					'type' => 'DATE',
					'null' => TRUE,
					'comment' => 'Start Date'
				),
				'endDate' => array(
					'type' => 'DATE',
					'null' => TRUE,
					'comment' => 'End Date'
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
				'imageUrl' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 255,
					'comment' => 'Campaign image URL '
					
				),
				'description' => array(
					'type' => 'TEXT',
					'null' => FALSE,
					'comment' => 'Campaign description'
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
				'quickLinkAvailable' => array(
					'type' => 'TINYINT',
					'null' => FALSE,
					'default' => 0,
					'comment' => 'Is QuickLink Available'

				),
				'affiliationStatus' => array(
                    'type' => 'ENUM',
                    'null' => FALSE,
                    'constraint' => [
						'APPROVED',
                        'APPLYING',
						'REJECTED',
						'NEW'
                    ]
				),
				'affiliatedDate' => array(
					'type' => 'TEXT',
					'null' => FALSE
				),
				'currency' => array(
					'type' => 'VARCHAR',
					'null' => FALSE,
					'constraint' => 3,
					'comment' => ''
				),
			)
		);
        $this->dbforge->add_field('`datetime_updated` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
		$this->dbforge->add_key('campaign_id', TRUE);
		$this->dbforge->create_table('campaign_data', TRUE);
	}
}

/* End of file 20200817163215_create_campaign_data.php */
/* Location: ./application/migrations/20200817163215_create_campaign_data.php */
