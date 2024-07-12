<?php defined('BASEPATH') or exit('No direct script access allowed');

class Menus extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();

        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
        // $this->load->model('admin/Activity_model', 'activity_model');
        $this->load->library('upload');

    }

    //-----------------------------------------------------------
    public function index()
    {
        $data['title'] = 'Menus';

        $this->load->view('backend/menus/index');
    }


    public function menus_datatable()
    {
        $banners = $this->common_model->get_data('ci_menu');
        $data = array();

        foreach ($banners as $key => $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';
            $link = $row['static_link'] ? $row['static_link'] : $row['llink'];
            $data[] = array(
                ++$key,
                $row['name'],
                $link,
                '<a title="Edit" style="display:inline-block" class="update btn btn-sm btn-warning" href="' . base_url('backend/menus/edit/' . $row['id']) . '"> <i class="fa fa-pencil-square-o"></i></a>'
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function edit()
    {
        $id = $this->uri->segment(4);
        $data['menu'] = $this->common_model->get_single_data('ci_menu', ['id' => $id]);
        $data['links'] = $this->common_model->get_data('cms_management');
        $this->load->view('backend/menus/edit', $data);
    }


    public function update()
    {
        $where = array();
        $id = $this->input->post('id');
        $data = array(
            'name' => $this->input->post('name'),
            'llink' => $this->input->post('link'),
            'static_link' => $this->input->post('static_link'),
            'status' => $this->input->post('status')
        );
        $where['id'] = $id;
        $result = $this->common_model->update_data('ci_menu', $where, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Menu has been changed Successfully!');
            redirect(base_url('backend/menus/index'), 'refresh');
        }
    }

}

?>