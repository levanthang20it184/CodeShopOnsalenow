<?php defined('BASEPATH') or exit('No direct script access allowed');

class Category extends CI_Controller
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
        $data['title'] = 'Category';

        $this->load->view('backend/category/index', $data);
    }


    public function category_datatable()
    {
        $category = $this->category_model->getCategort();

        $data = array();

        $i = 0;
        foreach ($category as $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';

            $subCategories = $this->category_model->getSubCategort($row['id']);

            $select = '<select class="osn-column form-control" style="width: 200px">';
            foreach ($subCategories as $subCategory) {
                $select .= "<option>" . $subCategory["name"] . "</option>";
            }

            $select .= "</select>";

            $data[] = array(
                ++$i,
                $row['name'],
                $select,
                // '<img alt="category image" width="100px" src="'.base_url().'assets/images/category/'.$row['image'].'">',
                ($row['created_at']),
                '<input class="tgl_checkbox tgl-ios" data-id="' . $row['id'] . '" id="cb_' . $row['id'] . '"
                type="checkbox" ' . $status . '><label for="cb_' . $row['id'] . '"></label>',
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('backend/category/view/' . $row['id']) . '"> <i class="fa fa-eye"></i></a>'
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
        $data['title'] = 'Category';
        $id = $this->uri->segment(4);

        $data['subCategories'] = $this->category_model->getSubCategort($id);

        $data['category'] = $this->common_model->get_single_data('ci_category', ['id' => $id]);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/category/edit', $data);
        }
    }

    public function removeSubCategory()
    {
        $subCategoryId = $this->input->post('subCategoryId');

        $this->common_model->removeSubCategory($subCategoryId);
        echo 'Sub category deleted successfully.';
    }

    public function renameSubCategory()
    {
        $subCategoryId = $this->input->post('subCategoryId');
        $newName = $this->input->post('newName');

        $this->common_model->renameSubCategory($subCategoryId, $newName);
        echo 'Sub category renamed successfully.';
    }

    public function addCategory()
    {
        $newName = $this->input->post('newName');

        $id = $this->common_model->addCategory($newName);
        echo json_encode(['Category added successfully.', $id]);
    }

    public function addSubCategory()
    {
        $categoryId = $this->input->post('categoryId');
        $newName = $this->input->post('newName');

        $id = $this->common_model->addSubCategory($categoryId, $newName);
        echo json_encode(['Sub category added successfully.', $id]);
    }

    public function update()
    {

        // echo "<pre>"; print_r($_POST);
        // echo "<pre>"; print_r($_FILES); die;

        $where = array();
        // $config['upload_path'] = '/disk2/images/uploads/category/';
        $config['upload_path'] = '/var/www/images.onsalenow.ie/uploads/category';

        $config['allowed_types'] = 'gif|jpg|png|webp';

        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $filename = '';

        if (($_FILES['file']['name'] != '')) {
            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('errors', $this->upload->display_errors());
                redirect(base_url('backend/category'));
            }
        }

        $data = array(
            'name' => $this->input->post('name'),
            'image' => $filename,
            'meta_title' => $this->input->post('meta_title'),
            'meta_tag' => $this->input->post('meta_tag'),
            'meta_description' => $this->input->post('meta_description'),
        );
        $where['id'] = $this->input->post('category_id');
        $result = $this->common_model->update_data('ci_category', $where, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Category has been changed Successfully!');
            redirect(base_url('backend/category/index'), 'refresh');
        }
    }
}
