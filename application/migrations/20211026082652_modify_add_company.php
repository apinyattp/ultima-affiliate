<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_add_company extends CI_Migration
{
    public function __construct()
    {
        $this->load->dbforge();
        $this->load->database();
    }

    public function up()
    {
        $this->add_company_table();
    }

    public function down()
    {
        $this->dbforge->drop_column('report_conversion', 'company');
        $this->dbforge->drop_column('missing_conversion', 'company');
    }

    private function add_company_table()
    {
        $fields = array(
            'company' => array(
                'type' => 'varchar',
                'null' => TRUE,
                'constraint' => 63,
                'comment' => 'company name',
            )
        );

        $this->dbforge->add_column('report_conversion', $fields, 'uid');
        $this->db->query('UPDATE report_conversion JOIN user ON user.jelala_id = report_conversion.uid SET report_conversion.company = user.company');
        $this->db->query('ALTER TABLE `report_conversion` ADD INDEX(`company`);');

        $this->dbforge->add_column('missing_conversion', $fields, 'uuid');
        $this->db->query('UPDATE missing_conversion JOIN user ON user.jelala_id = missing_conversion.uuid SET missing_conversion.company = user.company');
        $this->db->query('ALTER TABLE `missing_conversion` ADD INDEX(`company`);');
    }
}

/* End of file 20211026082652_modify_add_company.php */
/* Location: ./application/migrations/20211026082652_modify_add_company.php */
