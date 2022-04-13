<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_campaign_data_add_merchant_id extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->campaign_data_add_merchant_id_table();
    }

    public function down()
    {
        $this->dbforge->drop_column('campaign_data', 'merchant_id');
    }

    private function campaign_data_add_merchant_id_table()
    {
        $fields = array(
            'merchant_id' => array(
                'type' => 'VARCHAR',
                'null' => TRUE,
                'constraint' => 127,
				'after' => 'campaign_id',
            ),
        );

        $this->dbforge->add_column('campaign_data', $fields);
    }
}

/* End of file 20220412073714_modify_campaign_data_add_merchant_id.php */
/* Location: ./application/migrations/20220412073714_modify_campaign_data_add_merchant_id.php */
