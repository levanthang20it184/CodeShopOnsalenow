<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Common extends CI_Controller
{

    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend/common_model');
        $this->load->model('frontend/front_model');
        $this->load->model('frontend/products_model');

        $categorywithsub = $this->front_model->getCategorywithsub();
        $topcategory = $this->front_model->getTopCategoryList();

        $pages = $this->common_model->getCMSPages();
        $this->data = array(
            'categorywithsub' => $categorywithsub,
            'topcategory' => $topcategory,
            'pages' => $pages
        );
    }

    public function index()
    {
        $data = $this->data;
        $where = array();
        $category = $this->uri->segment(1);
        // echo $category; die;
        $subcate = is_numeric($this->uri->segment(2)) ? '' : $this->uri->segment(2);
        // echo $subcate; die;
        if ($category == '') {
            $data['title'] = 'All Categories';
            $this->load->view('frontend/category/allCategory', $data);
            return false;
        }
        if ($subcate != '')
            $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));

        $data['metadetails'] = getMetaDetailsByCategorySlug($category);
        $category = $this->common_model->get_single_data('ci_category', array('slug' => $category));
        $subtitle = isset($subcategory['name']) ? " | " . $subcategory['name'] : '';
        $data['title'] = "Onsalenow | " . ucfirst($category['name']) . $subtitle;
        $data['page'] = $this->uri->segment(3) ? $this->uri->segment(3) : (is_numeric($this->uri->segment(2)) ? $this->uri->segment(2) : 1);
        $where['category_id'] = $category['id'];
        $data['category'] = $category;
        $data['subcategory'] = @$subcategory;
        $data['category_id'] = $category['id'];
        $data['subcategory_id'] = @$subcategory['id'];
        $data['subcategories'] = $this->front_model->get_data('ci_subcategory', $where);

        $data['brands'] = $this->front_model->getBrandByCate(@$category['id'], @$subcategory['id']);

        $data['discount'] = $this->front_model->getDiscount();
        $data['name'] = isset($subcategory['name']) ? $subcategory['name'] : $category['name'];
        $data['image'] = $category['image'];

        $data['description'] = isset($subcategory['description']) ? $subcategory['description'] : $category['description'];

        $this->load->view('frontend/category/index', $data);
    }

    public function subcategory()
    {

        $data = $this->data;
        $where = array();
        $category = $this->uri->segment(1);
        // echo $category; die;
        $subcate = is_numeric($this->uri->segment(2)) ? '' : $this->uri->segment(2);
        // echo $subcate; die;
        if ($category == '') {
            $data['title'] = 'All Categories';
            $this->load->view('frontend/category/allCategory', $data);
            return false;
        }
        if ($subcate != '')
            $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));

        $data['metadetails'] = getMetaDetailsByCategorySlug($category);
        $category = $this->common_model->get_single_data('ci_category', array('slug' => $category));
        $subtitle = isset($subcategory['name']) ? " | " . $subcategory['name'] : '';
        $data['title'] = "Onsalenow | " . ucfirst($category['name']) . $subtitle;
        $data['page'] = $this->uri->segment(3) ? $this->uri->segment(3) : (is_numeric($this->uri->segment(2)) ? $this->uri->segment(2) : 1);
        $where['category_id'] = $category['id'];
        $data['category'] = $category;
        $data['subcategory'] = @$subcategory;
        $data['category_id'] = $category['id'];
        $data['subcategory_id'] = @$subcategory['id'];
        $data['subcategories'] = $this->front_model->get_data('ci_subcategory', $where);

        $data['brands'] = $this->front_model->getBrandByCate(@$category['id'], @$subcategory['id']);

        $data['discount'] = $this->front_model->getDiscount();
        $data['name'] = isset($subcategory['name']) ? $subcategory['name'] : $category['name'];
        $data['image'] = $category['image'];


        $data['description'] = isset($subcategory['description']) ? $subcategory['description'] : $category['description'];

        $this->load->view('frontend/category/index', $data);

    }
}

?>