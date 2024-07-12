<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron_Report extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $data['title'] = "Cron Report";
        $this->load->view('backend/cron_report/index', $data);
    }

    public function cron_report_datatable()
    {
        $contact = $this->common_model->getCronReports('cron_report');
        $data = array();

        $i = 0;
        foreach ($contact as $row) {
            $data[] = array(
                ++$i,
                $row['merchant_name'],
                $row['detail'],
                $row['date']
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }
}

?>