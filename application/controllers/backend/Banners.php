<?php defined('BASEPATH') or exit('No direct script access allowed');

class Banners extends CI_Controller
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
        $data['title'] = 'Banner';

        $this->load->view('backend/banners/index');
    }


    public function banner_datatable()
    {
        $banners = $this->common_model->get_data('ci_banners');
        $data = array();

        $i = 0;
        foreach ($banners as $row) {
            $status = ($row->status == 1) ? 'checked' : '';
            $row->banner_image = $row->banner_image ? $row->banner_image : 'default-banner.jpg';
            $data[] = array(
                ++$i,
                '<img alt="banner image" src="https://onsalenow99.b-cdn.net/uploads/banners/' . $row->banner_image . '" width="100">',
                $row->title,
                $row->description,
                ($row->created_at),
                '<a title="Edit" style="display: inline-flex" class="update btn btn-sm btn-warning" href="' . base_url('backend/banners/edit/' . $row->id) . '"> <i class="fa fa-pencil-square-o"></i></a> '
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }

    public function add()
    {
        $data['title'] = "Banner";
        if ($this->input->post('submit')) {
            // $this->form_validation->set_rules('title', 'Banner title', 'required');
            // $this->form_validation->set_rules('heading', 'Banner Heading', 'required');

            // if (empty($_FILES['image']['name'])) {
            //     $this->form_validation->set_rules('image', 'Banner Image', 'required');
            // }

            // if ($this->form_validation->run() == FALSE) {
            //  $data = array(
            //      'errors' => validation_errors()
            //  );
            //  $this->load->view('backend/banners/add', $data);
            // } else {

            $full_path = '';
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = './uploads/banners/';
                $config['allowed_types'] = 'jpg|png|jpeg|gif|webp';
                $config['max_width'] = '5000';
                $config['max_height'] = '5000';
                $config['overwrite'] = TRUE;
                $imageurl = date('U') . '_' . $_FILES['image']['name'];
                $config['file_name'] = str_replace(' ', '_', $imageurl);
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('image')) {
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                    redirect(base_url('backend/banners/add', $data), 'refresh');
                } else {
                    if ($this->upload->do_upload('image')) {
                        $image_data = $this->upload->data();
                        $full_path = $config['file_name'];
                    }
                }
            }

            $data = array(
                'banner_image' => $full_path,
                'title' => $this->input->post('title'),
                'banner_heading' => $this->input->post('heading'),
                'description' => $this->input->post('description'),
                'banner_name' => 'Banner Screen Right Top',
                'created_at' => date('Y-m-d : h:m:s'),
                'updated_at' => date('Y-m-d : h:m:s'),
            );
            $result = $this->common_model->add_data('ci_banners', $data);

            if ($result) {
                $this->session->set_flashdata('success', 'Banner has been added successfully!');
                redirect(base_url('backend/banners'));
            }
            //  }
        } else {
            $this->load->view('backend/banners/add', $data);
        }

    }

    public function edit($id = 0)
    {
        $data['title'] = "Banner";
        $where = array();
        $data['banner'] = $this->common_model->get_single_data('ci_banners', ['id' => $id]);
        $data['positions'] = $this->common_model->get_data('ci_banners');

        if ($this->input->post('submit')) {
            //    $this->form_validation->set_rules('title', 'Banner title', 'required');
            //    $this->form_validation->set_rules('heading', 'Banner Heading', 'required');
            // if (empty($_FILES['image']['name'])) {
            //  $this->form_validation->set_rules('image', 'Banner Image', 'required');
            // }
            // if ($this->form_validation->run() == FALSE) {
            //      $data = array(
            //          'errors' => validation_errors()
            //      );
            //      $this->session->set_flashdata('errors', $data['errors']);
            //      redirect(base_url('backend/banners/edit/'.$id),'refresh');
            // }
            // else{
            $full_path = $data['banner']['banner_image'];

            if (!empty($_FILES['image']['name'])) {
                // $config['upload_path'] = '/disk2/images/uploads/banners';
                $config['upload_path'] = '/var/www/images.onsalenow.ie/uploads/banners';

                $config['allowed_types'] = 'jpg|png|jpeg|gif|webp';
                $config['max_width'] = '5000';
                $config['max_height'] = '5000';
                $config['overwrite'] = TRUE;
                $imageurl = date('U') . '_' . $_FILES['image']['name'];
                $config['file_name'] = str_replace(' ', '_', $imageurl);
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('image')) {
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                    redirect(base_url('backend/banners/edit'), 'refresh');
                } else {
                    if ($this->upload->do_upload('image')) {
                        $image_data = $this->upload->data();
                        $full_path = $config['file_name'];

                    }
                }
            }

            $data = array(
                'banner_image' => $full_path,
                'title' => $this->input->post('title'),
                'banner_heading' => $this->input->post('heading'),
                'description' => $this->input->post('description'),
                'button_link' => $this->input->post('button_link'),
                'button_label' => $this->input->post('button_label'),
                'updated_at' => date('Y-m-d : h:m:s'),
            );
            $where['id'] = $id;
            $result = $this->common_model->update_data('ci_banners', $where, $data);
            if ($result) {
                $this->session->set_flashdata('success', 'Banner has been updated successfully!');
                redirect(base_url('backend/banners'));
            }
            // }
        } else {
            $this->load->view('backend/banners/edit', $data);
        }
    }

    public function delete($id = 0)
    {
        $this->db->delete('ci_banners', array('id' => $id));
        return true;
    }

}

?>