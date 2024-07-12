<?php defined('BASEPATH') or exit('No direct script access allowed');

class Brand extends CI_Controller
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
        $data['title'] = 'Brand';
        $noImageBrandCnt = $this->common_model->getNoImageBrandCnt();

        $data['noImageBrandCnt'] = $noImageBrandCnt;

        $this->load->view('backend/brand/index', $data);
    }

    public function brand_datatable()
    {
        $brand = $this->common_model->getBrandData();

        $data = array();

        $i = 0;
        foreach ($brand as $row) {
            $status = ($row['status'] == 1) ? 'checked' : '';
            $is_image = ($row['is_image'] == 1) ? 'checked' : '';
            $show_home = ($row['show_home'] == 1) ? 'checked' : '';

            if (isset($row['image']) && !empty($row['image']) && !($row['image'] == 'image')) {

                $pos = strpos($row['image'], "/");
                if ($pos === false) {
                    $brand_img_path = $row['image'];
                } else {
                    $brand_img_path = $row['image'];
                }
            } else {
                $brand_img_path = "http://onsalenow.ie/uploads/brand/default.jpg";
            }

            $data[] = array(
                ++$i,
                $row['brand_name'],
                $row['alias'],
                $row['slug'],
                '<img alt="brand image" width="100px" src="' . $brand_img_path . '">',
                $row['product_count'],
                json_encode([$status, $row['id']]),
                json_encode([$is_image, $row['id']]),
                json_encode([$show_home, $row['id']]),
                '<a title="View" class="view btn btn-sm btn-info" href="' . base_url('backend/brand/view/' . $row['id']) . '"> <i class="fa fa-edit"></i></a>
				<button title="View" class="view btn btn-sm btn-info" onclick="getProductCount(' . $row['id'] . ',' . $row['category_id'] . ',' . $row['subCategory_id'] . ')"> <i class="fa fa-cart-plus"></i></button>'
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
        return $this->common_model->update_data('ci_brand', $where, $data);
    }

    public function change_is_image()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'is_image' => $this->input->post('is_image')
        );
        return $this->common_model->update_data('ci_brand', $where, $data);
    }

    public function change_show_home()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'show_home' => $this->input->post('show_home')
        );
        return $this->common_model->update_data('ci_brand', $where, $data);
    }

    public function getProductCount()
    {
        $productCount = $this->common_model->getProductCount(
            $this->input->post('brandId'),
            $this->input->post('categoryId'),
            $this->input->post('subCategoryId')
        );

        echo $productCount;
    }

    public function fetchLogos()
    {
        $noImageBrands = $this->common_model->getNoImageBrands();

        foreach ($noImageBrands as $noImageBrand) {
            $searchTerm = "brand logo for " . $noImageBrand['brand_name'];

            sleep(2);

            // Fetch the Google Images search page for the search term
            $html = file_get_contents('https://www.google.com/search?q=' . urlencode($searchTerm) . '&tbm=isch');

            $dom = new DOMDocument();
            @$dom->loadHTML($html);
            $xpath = new DOMXPath($dom);

            $images = $xpath->query('//img');

            $imageLink = $images[1]->getAttribute('src');
            $this->common_model->updateBrandImageLink($noImageBrand['id'], $imageLink);
        }

        $data = array(
            'success' => true
        );

        echo json_encode($data);
    }

    public function view()
    {
        $data['title'] = 'Brand';
        $id = $this->uri->segment(4);
        $data['brand'] = $this->common_model->get_single_data('ci_brand', ['id' => $id]);
        $data['slugList'] = $this->common_model->getSlugList();
        $data['aliasList'] = $this->common_model->getAliasList();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('brand_name', 'Brand Name', 'trim|required');
            $this->form_validation->set_rules('description', 'Description', 'trim|required');
            $this->form_validation->set_rules('c_type', 'Type', 'trim|required');
        } else {
            $this->load->view('backend/brand/edit', $data);
        }
    }

    public function update()
    {
        $data = $_POST;
        $where = array();
        $config['upload_path'] = './images_db/brand_image/';
        $config['allowed_types'] = 'gif|jpg|png|webp';

        if ($_FILES['file']['name'] != '') {
            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            if ($this->upload->do_upload('file')) {
                $uploadData = $this->upload->data();
                $filename = $uploadData['file_name'];
            } else {
                $this->session->set_flashdata('errors', $this->upload->display_errors());
                redirect(base_url('backend/brand'));
            }
            $data = array(
                'image' => '/images_db/brand_image/' . $filename,
                'meta_title' => $this->input->post('meta_title'),
                'meta_tag' => $this->input->post('meta_tag'),
                'meta_description' => $this->input->post('meta_description'),
                'brand_name' => $this->input->post('brand_name')
            );
        }

        unset($data['brand_id']);
        unset($data['submit']);

        // print_r($data); die;
        $where['id'] = $this->input->post('brand_id');
        $result = $this->common_model->update_data('ci_brand', $where, $data);
        if ($result) {
            $this->session->set_flashdata('success', 'Brand has been changed Successfully!');
            redirect(base_url('backend/brand'), 'refresh');
        }
    }
}
