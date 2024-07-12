<?php defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        ob_start();
        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
    }

    //-------------------------------------------------------------------------
    public function index()
    {
        $data['title'] = 'Profile';
        $where = array();
        $data['countries'] = $this->common_model->get_data('ci_countries');
        $id = $this->session->userdata('admin_id');
        $data['admin'] = $this->common_model->get_single_data('ci_admin', ['admin_id' => $id]);

        if ($this->input->post('submit')) {
            $full_path = $data['admin']['image'];
            if (!empty($_FILES['image']['name'])) {
                $config['upload_path'] = './uploads/users/';
                $config['allowed_types'] = 'jpg|png|jpeg|gif|webp';
                $config['max_width'] = '4000';
                $config['max_height'] = '4000';
                $config['overwrite'] = TRUE;
                $imageurl = date('U') . '_' . $_FILES['image']['name'];
                $config['file_name'] = str_replace(' ', '_', $imageurl);
                $this->load->library('upload', $config);
                $this->upload->initialize($config);

                if (!$this->upload->do_upload('image')) {
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                    redirect(base_url('admin/profile'), 'refresh');
                } else {
                    if ($this->upload->do_upload('image')) {
                        $image_data = $this->upload->data();
                        $full_path = $config['file_name'];

                    }
                }
            }
            $data = array(
                'username' => $this->input->post('username'),
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'email' => $this->input->post('email'),
                'phonecode' => $this->input->post('phonecode'),
                'mobile_no' => $this->input->post('mobile_no'),
                'image' => $full_path,
                'fb' => $this->input->post('facebook'),
                'tw' => $this->input->post('twitter'),
                'insta' => $this->input->post('instagram'),
                'ytube' => $this->input->post('youtube'),
                'updated_at' => date('Y-m-d : h:m:s'),
            );
            $where['admin_id'] = $id;
            $result = $this->common_model->update_data('ci_admin', $where, $data);
            if ($result) {
                $this->session->set_flashdata('success', 'Profile has been Updated Successfully!');
                redirect(base_url('backend/profile'), 'refresh');
            }
        } else {

            $data['title'] = 'Admin Profile';
            $this->load->view('backend/profile/index', $data);
        }
    }

    //-------------------------------------------------------------------------
    public function password()
    {
        $data['title'] = 'Change Password';
        $where = array();
        $id = $this->session->userdata('admin_id');

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('confirm_pwd', 'Confirm Password', 'trim|required|matches[password]');

            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('errors', $data['errors']);
                redirect(base_url('backend/profile/password'));
            } else {
                $data = array(
                    'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT)
                );
                // print_r($data); die;

                $where['admin_id'] = $id;
                $result = $this->common_model->update_data('ci_admin', $where, $data);
                if ($result) {
                    $this->session->set_flashdata('success', 'Password has been changed successfully!');
                    redirect(base_url('backend/profile/password'));
                }
            }
        } else {

            $data['user'] = $this->common_model->get_single_data('ci_admin', ['admin_id', $id]);
            $this->load->view('backend/profile/password', $data);
        }
    }
}

?>	