<?php defined('BASEPATH') or exit('No direct script access allowed');

class General_settings extends CI_Controller
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
    // General Setting View
    public function index()
    {

        $where = array();
        $where['id'] = 1;
        $data['general_settings'] = $this->common_model->get_single_data('ci_general_settings', $where);
        $data['languages'] = $this->common_model->get_data('ci_language', ['status', '1']);

        $data['title'] = 'General Setting';

        $this->load->view('backend/general_settings/index', $data);

    }

    //-------------------------------------------------------------------------
    public function add()
    {
        //$this->rbac->check_operation_access(); // check opration permission

        $data = array(
            'application_name' => $this->input->post('application_name'),
            'copyright' => $this->input->post('copyright'),
            'facebook_link' => $this->input->post('facebook_link'),
            'twitter_link' => $this->input->post('twitter_link'),
            'google_link' => $this->input->post('google_link'),
            'youtube_link' => $this->input->post('youtube_link'),
            'linkedin_link' => $this->input->post('linkedin_link'),
            'instagram_link' => $this->input->post('instagram_link'),
            'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key'),
            'recaptcha_site_key' => $this->input->post('recaptcha_site_key'),
            'recaptcha_lang' => $this->input->post('recaptcha_lang'),
            'created_date' => date('Y-m-d : h:m:s'),
            'updated_date' => date('Y-m-d : h:m:s'),
        );

        $old_logo = $this->input->post('old_logo');
        $old_favicon = $this->input->post('old_favicon');
        $old_eu_icon = $this->input->post('old_eu_icon');
        // $config['upload_path'] = '/disk2/images/assets/img/';  // Specify the upload directory
        $config['upload_path'] = '/var/www/images.onsalenow.ie/assets/img';

        $config['allowed_types'] = 'gif|jpg|jpeg|png';  // Specify allowed file types
        $config['max_size'] = 2048;  // Set the maximum file size in kilobytes
        $this->load->library('upload', $config);

        $path = "/assets/img/";

        if (!empty($_FILES['logo']['name'])) {
            unlink('/var/www/images/'.$old_logo);
            if ($this->upload->do_upload('logo')) {
                $_data = $this->upload->data();
                $data['logo'] = $path.$_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $result['msg']);
                redirect(base_url('admin/general_settings'), 'refresh');
            }
        }

        if (!empty($_FILES['eu_icon']['name'])) {
            unlink('/var/www/images/'.$old_logo);
            $_data = $this->upload->data();
            if ($this->upload->do_upload('eu_icon')) {
                $data['eu_icon'] = $path.$_data['eu_icon'];
            } else {
                $this->session->set_flashdata('error', $result['msg']);
                redirect(base_url('admin/general_settings'), 'refresh');
            }
        }

        // favicon
        if (!empty($_FILES['favicon']['name'])) {
            unlink('/var/www/images/'.$old_logo);
            if ($this->upload->do_upload('favicon')) {
                $_data = $this->upload->data();
                $data['favicon'] = $path.$_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $result['msg']);
                redirect(base_url('admin/general_settings'), 'refresh');
            }
        }

        if (!empty($_FILES['favicon_icon']['name'])) {
            unlink('/var/www/images/'.$old_logo);
            if ($this->upload->do_upload('favicon_icon')) {
                $_data = $this->upload->data();
                $data['favicon_icon'] = $path.$_data['file_name'];
            } else {
                $this->session->set_flashdata('error', $result['msg']);
                redirect(base_url('admin/general_settings'), 'refresh');
            }
        }

        // $data = $this->security->xss_clean($data);
        $result = $this->common_model->update_data('ci_general_settings', ['id' => 1], $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Setting has been changed Successfully!');
            redirect(base_url('backend/general_settings/index'), 'refresh');
        }
    }

    /*--------------------------
   Email Template Settings
--------------------------*/

    // ------------------------------------------------------------
    public function email_templates()
    {
        //$this->rbac->check_operation_access(); // check opration permission
        if ($this->input->post()) {
            $this->form_validation->set_rules('subject', 'Email Subject', 'trim|required');
            $this->form_validation->set_rules('content', 'Email Body', 'trim|required');
            if ($this->form_validation->run() == FALSE) {
                echo validation_errors();
            } else {

                $id = $this->input->post('id');

                $data = array(
                    'subject' => $this->input->post('subject'),
                    'body' => $this->input->post('content'),
                    'last_update' => date('Y-m-d H:i:s'),
                );
                $data = $this->security->xss_clean($data);
                $result = $this->setting_model->update_email_template($data, $id);
                if ($result) {
                    echo "true";
                }
            }
        } else {
            $data['title'] = '';
            $data['templates'] = $this->setting_model->get_email_templates();

            $this->load->view('admin/includes/_header');
            $this->load->view('admin/general_settings/email_templates/templates_list', $data);
            $this->load->view('admin/includes/_footer');
        }
    }

    // ------------------------------------------------------------
    // Get Email Template & Related variables via Ajax by ID
    public function get_email_template_content_by_id()
    {
        $id = $this->input->post('template_id');

        $data['template'] = $this->setting_model->get_email_template_content_by_id($id);

        $variables = $this->setting_model->get_email_template_variables_by_id($id);

        $data['variables'] = implode(',', array_column($variables, 'variable_name'));

        echo json_encode($data);
    }

    //---------------------------------------------------------------
    //
    public function email_preview()
    {
        if ($this->input->post('content')) {
            $data['content'] = $this->input->post('content');
            $data['head'] = $this->input->post('head');
            $data['title'] = 'Send Email to Subscribers';
            echo $this->load->view('admin/general_settings/email_templates/email_preview', $data, true);
        }
    }

}

?>  