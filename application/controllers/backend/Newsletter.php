<?php defined('BASEPATH') or exit('No direct script access allowed');

class Newsletter extends CI_Controller
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
        $data['title'] = "Contact";
        $this->load->view('backend/newsletter/index', $data);
    }

    public function newsletter_datatable()
    {
        $contact = $this->common_model->get_data('ci_newsletter');
        // echo "<pre>"; print_r($contact); die;
        $data = array();

        $i = 0;
        foreach ($contact as $row) {
            $data[] = array(
                ++$i,
                $row->email,
                '<a title="View" style="display: inline-flex" class="update btn btn-sm btn-warning" href="' . base_url('backend/newsletter/view/' . $row->id) . '"> <i class="fa fa-eye"></i></a> '
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function view($id = '')
    {
        $data['title'] = "Newsletter";
        $data['contact'] = $this->common_model->get_single_data('ci_newsletter', ['id' => $id]);
        $this->load->view('backend/newsletter/view', $data);
    }

    public function change_status()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'status' => $this->input->post('status')
        );
        return $this->common_model->update_data('ci_contact', $where, $data);


    }

}

?>