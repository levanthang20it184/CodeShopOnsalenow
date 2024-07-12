<?php defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
    }

    //--------------------------------------------------------------------------

    public function index()
    {

        $data['title'] = 'Dashboard';
        $data['all_cataegory'] = $this->common_model->get_data_count('ci_category');
        $data['all_products'] = $this->common_model->get_data_count('ci_products');
        $data['all_brands'] = $this->common_model->get_data_count('ci_brand');

        $this->load->view('backend/dashboard/index', $data);

    }

}

?>