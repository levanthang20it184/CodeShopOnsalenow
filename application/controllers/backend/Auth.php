<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    var $data;

    public function __construct()
    {

        parent::__construct();
        $this->load->helper('auth');

        // $this->load->library('mailer');
        $this->load->model('backend/auth_model');
        $this->load->model('backend/common_model');
        $this->load->library('session');


        $this->data = array(
            'navbar' => false,
            'sidebar' => false,
            'footer' => false,
            'bg_cover' => true
        );

    }



    // public function login()
    // {

    //     if ($this->input->post('submit')) {

    //         $this->form_validation->set_rules('username', 'Username', 'trim|required');
    //         $this->form_validation->set_rules('password', 'Password', 'trim|required');

    //         if ($this->form_validation->run() == FALSE) {
    //             $data = array(
    //                 'errors' => validation_errors()
    //             );
    //             $this->session->set_flashdata('error', $data['errors']);
    //             redirect(base_url('backend/auth/login'), 'refresh');
    //         } else {
    //             $data = array(
    //                 'username' => $this->input->post('username'),
    //                 'password' => $this->input->post('password')
    //             );
    //             $result = $this->auth_model->login($data);
    //             // dd($result);exit;
    //             if ($result) {
    //                 if ($result['is_verify'] == 0) {
    //                     $this->session->set_flashdata('error', 'Please verify your email address!');
    //                     redirect(base_url('backend/auth/login'));
    //                     exit();
    //                 }
    //                 if ($result['is_active'] == 0) {
    //                     $this->session->set_flashdata('error', 'Account is disabled by Admin!');
    //                     redirect(base_url('backend/auth/login'));
    //                     exit();
    //                 }
    //                 if ($result['is_admin'] == 1) {
    //                     $admin_data = array(
    //                         'admin_id' => $result['admin_id'],
    //                         'username' => $result['username'],
    //                         'admin_role_id' => 1,
    //                         'admin_role' => 'Super Admin',
    //                         'is_supper' => 0,
    //                         'is_admin_login' => TRUE
    //                     );
    //                     $this->session->set_userdata($admin_data);

    //                     redirect(base_url('backend/dashboard'), 'refresh');

    //                 }
    //             } else {
    //                 $this->session->set_flashdata('errors', 'Invalid Username or Password!');
    //                 redirect(base_url('backend/auth/login'));
    //             }
    //         }
    //     } else {
    //         $data['data'] = $this->data;
    //         $data['title'] = 'Login';

    //         $this->load->view('backend/auth/login', $data);
    //     }
    // }



    public function login() {
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $data = array(
                    'errors' => validation_errors()
                );
                $this->session->set_flashdata('error', $data['errors']);
                redirect(base_url('backend/auth/login'), 'refresh');
            } else {
                $data = array(
                    'username' => $this->input->post('username'),
                    'password' => $this->input->post('password')
                );
                $result = $this->auth_model->login($data);

                if ($result) {
                    if ($result['is_verify'] == 0) {
                        $this->session->set_flashdata('error', 'Please verify your email address!');
                        redirect(base_url('backend/auth/login'));
                        exit();
                    }
                    if ($result['is_active'] == 0) {
                        $this->session->set_flashdata('error', 'Account is disabled by Admin!');
                        redirect(base_url('backend/auth/login'));
                        exit();
                    }
                    if ($result['is_admin'] == 1) {
                        // $admin_email = 'tranthai220302@gmail.com';
                        $admin_email = ['mncolgan@gmail.com', 'cjae192004@gmail.com', 'hoangleduy27901@gmail.com'];
                        $admin_data = array(
                            'admin_id' => $result['admin_id'],
                            'username' => $result['username'],
                            'admin_role_id' => 1,
                            'admin_role' => 'Super Admin',
                            'is_supper' => 0,
                            'is_temp_login' => TRUE,
                            'is_change_password' => $result['is_change_password']
                        );
                        $this->session->set_userdata($admin_data);
                        // Tạo và gửi mã xác thực
                        $auth_code = generate_random_code();
                        $this->session->set_userdata('auth_code', $auth_code);
                        $this->session->set_userdata('admin_id', $result['admin_id']);
                        $message = "
                        <html>
                        <head>
                            <title>Two-Factor Authentication Code</title>
                            <style>
                                body {
                                    font-family: Arial, sans-serif;
                                }
                                .email-container {
                                    width: 600px;
                                    margin: 0 auto;
                                    padding: 20px;
                                    border: 1px solid #ddd;
                                    border-radius: 5px;
                                }
                                .logo {
                                    display: block;
                                    margin: 0 auto;
                                    width: 200px;
                                }
                                .auth-code {
                                    color: #2c3e50;
                                    background-color: #ecf0f1;
                                    padding: 10px;
                                    border-radius: 5px;
                                }
                                .auth-code-number{
                                    font-size: 1.17em;

                                }
                            </style>
                        </head>
                        <body>
                            <div class='email-container'>
                                <img class='logo' src='https://onsalenow99.b-cdn.net/assets/img/6e2c038e72144e4ea4053a753be173d2.png' alt='Onsale Team Logo'>
                                <h2>Dear Admin,</h2>
                                <p class='auth-code'>Your authentication code is: <span class='auth-code-number'><strong>" . $auth_code . "</strong></span>.</p>
                                <p>Please enter this code to complete your login process.</p>
                                <p>Best Regards,</p>
                                <p>Onsale Team</p>
                            </div>
                        </body>
                        </html>
                        ";
                        if ($this->sendEmail($admin_email, 'Two-Factor Authentication Code', $message)) {
                            $this->session->set_userdata('auth_code', $auth_code);
                            redirect(base_url('backend/auth/twoFactorAuth'), 'refresh');
                        } else {
                            echo "Failed to send email.";
                        }

                    }
                } else {
                    $this->session->set_flashdata('errors', 'Invalid Username or Password!');
                    redirect(base_url('backend/auth/login'));
                }
            }
        } else {
            $data['data'] = $this->data;
            $data['title'] = 'Login';

            $this->load->view('backend/auth/login', $data);
        }
    }

    public function twoFactorAuth() {

        if (!$this->session->userdata('is_temp_login')) {
            redirect(base_url('backend/auth/login'));
        }
            $this->load->view('backend/auth/2fa');
    }

    public function verify_2fa() {
        $auth_code = $this->input->post('auth_code');

        $saved_auth_code = $this->session->userdata('auth_code');
        if ($auth_code === $saved_auth_code) {
            $this->session->unset_userdata('auth_code');
            $this->session->unset_userdata('is_temp_login');
            $this->session->set_userdata('is_admin_login', TRUE);
            redirect(base_url('backend/dashboard'), 'refresh');
        }  else {
            $this->session->set_flashdata('error', 'Invalid authentication code!');
            redirect(base_url('backend/auth/twoFactorAuth'));
        }

    }

    public function sendEmail($to, $subject, $message) {
        $this->load->library('email');

        $config['protocol'] = 'smtp'; // Protocol to be used for sending emails
        $config['smtp_host'] = 'smtp.gmail.com'; // SMTP server address
        $config['smtp_port'] = '587'; // SMTP port
        $config['smtp_user'] = 'onsalenow.ie.mail@gmail.com'; // SMTP username
        $config['smtp_pass'] = 'jzyibitjnqgqfupj'; // SMTP password
        $config['smtp_crypto'] = 'tls'; // Encryption protocol (e.g., tls, ssl)
        $config['charset'] = 'utf-8'; // Character set
        $config['mailtype'] = 'html'; // Email format: text or html
        $config['newline'] = "\r\n"; // Newline character sequence



        $this->email->initialize($config);

        $this->email->from('onsalenow.ie.mail@gmail.com', 'Onsalenow');
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }




    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url('backend/auth/login'), 'refresh');
    }


    public function slugdata()
    {
        set_time_limit(0);
        ini_set("memory_limit", "512M");
        $like['slug'] = ' ';
        $category = $this->common_model->get_slug_data('ci_category', 'id, name', $like);
        $subcategory = $this->common_model->get_slug_data('ci_subcategory', 'id, name', $like);
        $brand = $this->common_model->get_slug_data('ci_brand', 'id, brand_name', $like);
        $products = $this->common_model->get_slug_data('ci_products', 'id, name', $like, '');

        foreach ($category as $key => $value) {
            $data = array();
            $where['id'] = $value['id'];
            $slug = str_replace("'", '', str_replace(' ', '-', str_replace('  ', ' ', str_replace('&', '', $value['name']))));
            $data['slug'] = $slug;
            $this->common_model->update_data('ci_category', $where, $data);
        }

        foreach ($subcategory as $key => $value) {
            $data = array();
            $where['id'] = $value['id'];
            $slug = str_replace("'", '', str_replace(' ', '-', str_replace('  ', ' ', str_replace('&', '', $value['name']))));
            $data['slug'] = $slug;
            $this->common_model->update_data('ci_subcategory', $where, $data);
        }

        foreach ($brand as $key => $value) {
            $data = array();
            $where['id'] = $value['id'];
            $slug = str_replace("'", '', str_replace(' ', '-', str_replace('  ', ' ', str_replace('&', '', $value['brand_name']))));
            $data['slug'] = $slug;
            $this->common_model->update_data('ci_brand', $where, $data);
        }

        foreach ($products as $key => $value) {
            $data = array();
            $where['id'] = $value['id'];
            $slug = str_replace('/', '-', str_replace("'", '', str_replace(' ', '-', str_replace('  ', ' ', str_replace('&', '', $value['name'])))));
            $data['slug'] = $slug;
            $this->common_model->update_data('ci_products', $where, $data);
        }

        redirect(base_url('backend/dashboard'), 'refresh');
    }


}

?>
