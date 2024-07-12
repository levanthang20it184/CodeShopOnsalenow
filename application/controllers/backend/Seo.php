<?php defined('BASEPATH') or exit('No direct script access allowed');

class Seo extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }

        $this->load->model('backend/common_model', 'common_model');
        $this->load->model('backend/category_model', 'category_model');
    }

    //-----------------------------------------------------------
    public function index()
    {
        $data['title'] = 'Product SEO';

        $this->load->view('backend/seo/index', $data);
    }

    public function product()
    {
        $data['title'] = 'Product SEO';
        $type = $this->uri->segment(4);
        // echo $id; die;
        $data['category'] = $this->common_model->get_metatag_data('ci_meta_tags', ['meta_type' => $type]);
        $data['type'] = $type;
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/seo/seoproduct/edit', $data);
        }
    }

    public function category()
    {
        $data['title'] = 'Category SEO';
        $type = $this->uri->segment(4);
        // echo $type; die;
        $data['category'] = $this->common_model->get_metatag_data('ci_meta_tags', ['meta_type' => $type]);
        $data['type'] = $type;
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/seo/seocategory/edit', $data);
        }
    }

    public function sub_category()
    {
        $data['title'] = 'Sub Category SEO';
        $type = $this->uri->segment(4);
        // echo $type; die;
        $data['category'] = $this->common_model->get_metatag_data('ci_meta_tags', ['meta_type' => $type]);
        $data['type'] = $type;
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/seo/seosubcategory/edit', $data);
        }
    }

    public function brand()
    {
        $data['title'] = 'Sub Brand SEO';
        $type = $this->uri->segment(4);
        // echo $type; die;
        $data['category'] = $this->common_model->get_metatag_data('ci_meta_tags', ['meta_type' => $type]);
        $data['type'] = $type;
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/seo/seobrand/edit', $data);
        }
    }


    public function category_datatable()
    {
        $category = $this->category_model->getProductTagsList();
        $data = array();

        // echo "<pre>"; print_r($category); die;

        $i = 0;
        foreach ($category as $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';

            $data[] = array(
                ++$i,
                $row['name'],
                // '<img alt="category image" width="100px" src="'.base_url().'assets/images/category/'.$row['image'].'">',
                ($row['created_at']),
                '<input class="tgl_checkbox tgl-ios" data-id="' . $row['id'] . '" id="cb_' . $row['id'] . '"
				type="checkbox" ' . $status . '><label for="cb_' . $row['id'] . '"></label>',
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('backend/seo/view/' . $row['id']) . '"> <i class="fa fa-edit"></i></a>'
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
        return $this->common_model->update_data('ci_category', $where, $data);


    }

    public function view()
    {
        $data['title'] = 'Product';
        $id = $this->uri->segment(4);
        $data['category'] = $this->common_model->get_single_data('ci_category', ['id' => $id]);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/seo/edit', $data);
        }
    }

    public function update()
    {

        // echo "<pre>"; print_r($_POST); die;
        $where = array();

        $data = array(
            'meta_title' => $this->input->post('meta_title'),
            'meta_tag' => $this->input->post('meta_tag'),
            'meta_description' => $this->input->post('meta_description'),
            'meta_h1' => $this->input->post('meta_h1'),
        );
        $meta_type = $this->input->post('meta_type');
        $where['meta_type'] = $meta_type;
        $result = $this->common_model->update_product_meta_tag('ci_meta_tags', $where, $data);
        if ($result) {
            $this->session->set_flashdata('success', ' ' . $meta_type . ' Details has been changed Successfully!');
            redirect(base_url('backend/seo/' . $meta_type . '/' . $meta_type), 'refresh');
        }
    }


}

?>