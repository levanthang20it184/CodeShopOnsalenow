<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cms extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
    }


    public function index()
    {
        $data['title'] = 'CMS';
        $this->load->view('backend/cms/index', $data);
    }

    public function cms_datatable()
    {
        $cms = $this->common_model->get_data('cms_management');
        $data = array();

        $i = 0;
        foreach ($cms as $row) {
            $status = ($row->status == 1) ? 'checked' : '';

            $data[] = array(
                ++$i,
                $row -> title,
                $row -> slug,
                ($row -> created_at),
                '<input class="tgl_checkbox tgl-ios"data-id="' . $row->id . '"id="cb_' . $row-> id . '"type="checkbox"' . $status . '><label for="cb_' . $row->id . '"></label>',
                '<a title="Edit" style="display: inline-flex" class="update btn btn-sm btn-warning" href="' . base_url('backend/cms/edit/' . $row->id) . '"> <i class="fa fa-pencil-square-o"></i></a> &nbsp;| &nbsp; <a title="Delete" style="display: inline-flex" deleteId=' . $row->id . ' deleteUrl="' . base_url('backend/cms/delete/' . $row->id) . '" class="delete cms-delete btn btn-sm btn-danger"> <i class="fa fa-trash-o"></i></a>'
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function change_status()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'status' => $this->input->post('status')
        );
        return $this->common_model->update_data('cms_management', $where, $data);


    }

    public function delete($id = 0)
    {
        $this->db->delete('cms_management', array('id' => $id));
        return true;
    }

    public function add()
    {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->load->view('backend/cms/add', $data);
            } else {

                $slug = str_replace(' ', '-', strtolower($this->input->post('title')));
                $data = array(
                    'title' => $this->input->post('title'),
                    'meta_title' => $this->input->post('meta_title'),
                    'description' => $this->input->post('description'),
                    'slug' => $slug,
                    'date' => $this->input->post('date'),
                    'type' => $this->input->post('type'),
                    'created_at' => date('Y-m-d : h:m:s'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                    'achieved' => isset($_POST['achieved'])?1:0
                );

                $result = $this->common_model->add_data('cms_management', $data);
                if ($result) {
                    $this->session->set_flashdata('success', 'CMS has been added successfully!');
                    redirect(base_url('backend/cms'));
                }
            }
        } else {
            $this->load->view('backend/cms/add');
        }

    }

    public function edit($id = 0)
    {

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('title', 'Title', 'trim|required');
            $this->form_validation->set_rules('meta_title', 'Meta Title', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('status', 'Status', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->load->view('backend/cms/edit', $data);
            } else {
                $where = array();
                $where['id'] = $id;
                $data = array(
                    'title' => $this->input->post('title'),
                    'meta_title' => $this->input->post('meta_title'),
                    'description' => $this->input->post('description'),
                    'status' => $this->input->post('status'),
                    'updated_at' => date('Y-m-d : h:m:s'),
                    'slug' => $this->input->post('slug'),
                    'date' => $this->input->post('date'),
                    'type' => $this->input->post('type'),
                    'achieved' => isset($_POST['achieved'])?1:0
                );
                $result = $this->common_model->update_data('cms_management', $where, $data);
                if ($result) {
                    $this->session->set_flashdata('success', 'CMS has been updated successfully!');
                    redirect(base_url('backend/cms'));
                }
            }
        } else {
            $data['cms'] = $this->common_model->get_single_data('cms_management', ['id' => $id]);
            $this->load->view('backend/cms/edit', $data);
        }
    }

}

?>