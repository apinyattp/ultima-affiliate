<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Modify_report_conversion_add_remark extends CI_Migration
{
	public function __construct()
	{
		$this->load->dbforge();
		$this->load->database();
	}

	public function up()
	{
		$this->report_conversion_add_remark_table();
	}

	public function down()
	{
		$this->dbforge->drop_column('report_conversion', 'remark');
	}

	private function report_conversion_add_remark_table()
	{
		$fields = array(
			'remark' => array(
                'type' => 'varchar',
                'null' => TRUE,
                'constraint' => 63,
                'comment' => 'remark',
				'after' => 'missing_id',
			),
		);

		$this->dbforge->add_column('report_conversion', $fields);
	}
}

/* End of file 20220307030334_modify_report_conversion_add_remark.php */
/* Location: ./application/migrations/20220307030334_modify_report_conversion_add_remark.php */
