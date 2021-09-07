<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->config('affiliate/main');
    }

    public function index(){
        redirect('cms/report/list');
    }

    public function list() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();
        
        $this->head->js_add('js/report/list.js');

        $company = $a_admin['role'] == 'admin' ? $this->input->get('company') : $a_admin['role'];
        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $period_base = $this->input->get('period_base');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $page = $this->input->get('page');
        $perpage = $this->input->get('perpage');
        $sort = $this->input->get('sort');
        $source = $this->input->get('source');

        $period_base = empty($period_base) ? 'datetime_updated' : $period_base;
        // $start_date = empty($start_date) ? date('Y-m-d') : $start_date;
        // $end_date = empty($end_date) ? date('m/d/Y') : $end_date;

        $page = max(1, $page);
        $perpage = empty($perpage) ? 10 : $perpage;

        $a_sort = [
            'conversion_id_desc' => 'conversion_id DESC',
            'conversion_time_asc' => 'conversion_time ASC',
            'conversion_time_desc' => 'conversion_time DESC',
            'datetime_updated_asc' => 'datetime_updated ASC, conversion_time DESC',
            'datetime_updated_desc' => 'datetime_updated desc, conversion_time DESC',
        ];
        if(!isset($a_sort[$sort])) $sort = 'datetime_updated_desc';

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $status = (isset($a_status[$status])) ? $a_status[$status] : NULL;

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list($period_base, $start_date, $end_date, $keyword, $campaign_id, $status, $a_sort[$sort], $source, $company);

        $this->load->library('qs');
        $qs_conversion->page($page, $perpage);

        $a_conversion = $qs_conversion->result('cms/report/conversion/list');

        $this->load->model('campaign_model');
        $qs_campaign = $this->campaign_model->get_list(FALSE, FALSE, FALSE, 'name ASC');

        $a_campaign = $qs_campaign->result('cms/campaign/list', TRUE);

        // GET SUMMARY
        $a_summary = [
            'pending' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'PENDING', FALSE, $company),
            'approved' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'APPROVED', FALSE, $company),
            'rejected' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, 'REJECTED', FALSE, $company),
            'total' => $this->report_conversion_model->get_summary($period_base, $start_date, $end_date, $keyword, $campaign_id, FALSE, FALSE, $company),
            'missing_total' => count($this->report_conversion_model->get_missing_order())
        ];

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'a_summary' => $a_summary,
            'a_conversion' => $a_conversion,
            'a_campaign' => $a_campaign['lists'],
            'keyword' => $keyword,
            'status' => $status,
            'period_base' => $period_base,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'campaign_id' => $campaign_id,
            'company' => $company,
            'companies' => $this->config->item('companies'),
            'role' => $a_admin['role']
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/list/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function detail($id) {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');
        $a_admin = $this->_auth_admin();

        $this->load->model('report_conversion_model');
        $conversion = $this->report_conversion_model->get_by_id($id);
        if(empty($conversion)) redirect('cms/report/list');

        $a_header_data = [
            'page' => 'report',
            'a_admin' => $a_admin
        ];

        $a_data = [
            'conversion' => $this->format->run('cms/report/conversion/detail', $conversion)
        ];

        $this->load->view('cms/template/header', $a_header_data);
        $this->load->view('cms/report/detail/index', $a_data);
        $this->load->view('cms/template/footer');
    }

    public function export() {
        if(($auth = $this->_admin_authorization()) !== TRUE) redirect('cms/admin');

        $a_admin = $this->_auth_admin();
        $company = $a_admin['role'] == 'admin' ? $this->input->get('company') : $a_admin['role'];
        $keyword = $this->input->get('keyword');
        $status = $this->input->get('status');
        $period_base = $this->input->get('period_base');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $campaign_id = $this->input->get('campaign_id');
        $source = $this->input->get('source');

        $period_base = empty($period_base) ? 'datetime_updated' : $period_base;

        $a_status = ['pending' => 'PENDING', 'approved' => 'APPROVED', 'rejected' => 'REJECTED'];

        $status = (isset($a_status[$status])) ? $a_status[$status] : NULL;

        $this->load->model('report_conversion_model');
        $qs_conversion = $this->report_conversion_model->get_list($period_base, $start_date, $end_date, $keyword, $campaign_id, $status, FALSE, $source, $company);

        $this->load->library('qs');

        $a_header = [
            'Company',
            'Conversion ID',
            'Campaign',
            'Uid',
            'Cashback',
            'Transaction Amount',
            'Transaction ID',
            'Click Time',
            'Conversion Time',
            'Confirmation Time',
            'Updaeted Time',
            'Status',
            'Missing Conversion'
        ];

        $qs_conversion->export('conversion_report', 'csv', $a_header, 'cms/report/conversion/export');

    }

    public function import_conversion() {
        $a_upload = [];
        $a_error = [];
        $filename= $_FILES["file"]["tmp_name"]; 
        
        $this->load->model('campaign_model');
        $this->load->model('user_model');
        $this->load->model('report_conversion_model');

        if($_FILES["file"]["size"] > 0) {
            $handle = fopen($filename,"r");
            $row = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if($row == 0) {
                    $header = $data;
                    if(count($header) != 15) {
                        $a_error[$row] = [
                            'header ผิด'
                        ];
                    }
                    $row++;
                    continue; 
                }

                for($index = 0; $index < count($data); $index++) {
                    if($index == 7 || $index == 8 || $index == 12) continue;
                    if(empty($data[$index])) {
                        $a_error[$row] = [
                            'บรรทัดที่ '. $row . ' : ' . $header[$index] . ' ไม่มีข้อมูลใน csv'
                        ];
                    }
                }
                $campaign = $this->campaign_model->get_by_id($data[1]);
                if(empty($campaign)) {
                    $a_error[$row] = [
                        'บรรทัดที่ '. $row . ' : campaign id: ' . $data[1] . ' ไม่มีในระบบ'
                    ];
                }else{
                    $data[] = $campaign['source'];
                    $data[] = $campaign['display_name'];
                }
                
                if(!in_array(strtoupper($data[9]), ['PENDING', 'APPROVED','REJECTED', 'NEW', 'PAID', 'INVALID'])) {
                    $a_error[$row] = [
                        'บรรทัดที่ '. $row . ' :  status : ' . $data[9] . ' ไม่ถูกต้อง'
                    ];
                }
                if(strtoupper($data[9]) == 'PAID' && empty($data[8])) {
                    $a_error[$row] = [
                        'บรรทัดที่ '. $row . ' :  ต้องระบุ paid time'
                    ];
                }
                if(strtoupper($data[9]) == 'APPROVED' && empty($data[7])) {
                    $a_error[$row] = [
                        'บรรทัดที่ '. $row . ' :  ต้องระบุ confirmation time'
                    ];
                }
                if(!in_array(ucfirst($data[2]), $this->config->item('companies'))) {
                    $a_error[$row] = [
                        'บรรทัดที่ '. $row . ' :  Company ไม่ถูกต้อง'
                    ];
                }
               
                if(empty($a_error[$row])) {
                    $uuid_data = $this->user_model->check_by_uuid($data[3]);
                    if(empty($uuid_data)) {
                        $uuid_data = $this->user_model->create_user_relation($data[3], strtolower($data[2]));
                    }else{
                        if($uuid_data['company'] != strtolower($data[2])) {
                            $a_error[$row] = [
                                'บรรทัดที่ '. $row . ' :  Company ไม่ตรงกับ uuid ในระบบ'
                            ];
                        }
                    }
                    $a_upload[] = $data;
                }
   
                $row++;
            }
            fclose($handle);

            $status = 'success';
            if(!empty($a_error)) {
                $status = 'fail';
            }else{
                foreach($a_upload as $upload) {
                    $this->report_conversion_model->update_by_conversion_id2(
                        $upload[0],
                        $upload[15],
                        $upload[3],
                        '194802',
                        'Jelala',
                        $upload[1],
                        $upload[16],
                        NULL,
                        NULL,
                        NULL,
                        $upload[4],
                        date('Y-m-d H:i:s', strtotime($upload[5])),
                        date('Y-m-d H:i:s', strtotime($upload[6])),
                        empty($upload[7] || strtoupper($upload[7]) == 'NULL' ) ? NULL : date('Y-m-d H:i:s', strtotime($upload[7])) ,
                        strtoupper($upload[9]),
                        $upload[10],
                        $upload[10],
                        $upload[11],
                        $upload[11],
                        'THB',
                        NULL,
                        NULL,
                        NULL,
                        empty($upload[12]) ? '' : json_encode($upload[12]),
                        NULL,
                        0,
                        empty($upload[8] || strtoupper($upload[8]) == 'NULL' ) ? NULL : $upload[8]
                    );
                }
            }

            $this->_echo_json(E::SUCCESS, ['status' => $status, 'error' => array_values($a_error)]); 
        }  

    }

}
