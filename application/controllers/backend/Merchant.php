<?php defined('BASEPATH') or exit('No direct script access allowed');

class Merchant extends CI_Controller
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
        $data['title'] = 'Merchant';
        $this->load->view('backend/merchant/index', $data);
    }

    public function merchant_datatable()
    {
        $merchant = $this->common_model->get_data('ci_merchant');
        $data = array();

        $i = 0;
        foreach ($merchant as $row) {
            $status = ($row->eu_icon_status == 1) ? 'Yes' : 'No';
            $data[] = array(
                ++$i,
                $row->merchant_name,
                $row->shipping_cost,
                $row->specific_promotion,
                $status,
                ($row->created_at),
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('backend/merchant/edit/' . $row->id) . '"> <i class="fa fa-eye"></i></a>'
            );
        }
        $records['data'] = $data;
        echo json_encode($records);
    }


    public function edit($id = 0)
    {
        $data['title'] = 'Merchant';
        $where = array();

        if ($this->input->post('submit')) {
            // dd($this->input->post());
            // dd($_FILES);

            $arrayName = array(
                'shipping_cost' => $this->input->post('shipping_cost'),
                'shipping_days' => $this->input->post('shipping_days'),
                'specific_promotion' => $this->input->post('specific_promotion'),
                'eu_icon_status' => $this->input->post('eu_icon'),

            );
            if (!empty($_FILES['file']['name'])) {
                $config['upload_path'] = './uploads/merchant/';
                $config['allowed_types'] = 'gif|jpg|png|webp';
                $config['max_size'] = 2000;
                $config['max_width'] = 2000;
                $config['max_height'] = 2000;

                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $uploadData = $this->upload->data();
                    $filename = $uploadData['file_name'];
                    $arrayName['image'] = $filename;
                } else {
                    dd('error');
                    $this->session->set_flashdata('errors', $this->upload->display_errors());
                    redirect(base_url('backend/brand'));
                }
            }
            $where['id'] = $id;
            $result = $this->common_model->update_data('ci_merchant', $where, $arrayName);
            if ($result) {
                $this->session->set_flashdata('success', 'Data has been updated successfully!');
                redirect(base_url('backend/merchant'));
            }
        } else {
            $data['deal'] = $this->common_model->get_single_data('ci_merchant', ['id' => $id]);
            $this->load->view('backend/merchant/edit', $data);
        }
    }


}

?>