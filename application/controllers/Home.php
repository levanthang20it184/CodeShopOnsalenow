<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{
    var $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend/common_model');
        $this->load->model('frontend/front_model');
        $this->load->model('frontend/products_model');

        // get menu and sub menu list
        $categorywithsub = $this->front_model->getCategorywithsub();

        // get hot category list
        $topcategory = $this->front_model->getTopCategoryList();
        $pages = $this->common_model->getCMSPages();
        $this->data = array(
            'categorywithsub' => $categorywithsub,
            'topcategory' => $topcategory,
            'pages' => $pages
        );        
        // $this->output->enable_profiler(TRUE);

    }

    public function index()
    {
        $data = $this->data;
        $data['title'] = 'Home';

        // get top50 product list
        $data['topfifty'] = $this->front_model->get_top_products();

        // get top deal product list
        $data['topdeals'] = $this->front_model->get_topdealsProduct();

        // get brand list setten as home show
        $data['brands'] = $this->front_model->get_branddata();

        $data['admin_data'] = $this->db->where('admin_id', $this->session->userdata('admin_id'))->get('ci_admin')->row();

        $this->load->view('frontend/index', $data);
        // $this->output->enable_profiler(TRUE);

    }

    public function pages()
    {
        $data = $this->data;
        $slug = $this->uri->segment(2);
        $page = $this->common_model->get_single_data('cms_management', array('slug' => $slug));

        if (empty($page)) {
            $data['title'] = '404 Page Not found';
            $this->load->view('errors/404', $data);
            return false;
        }
        $data['title'] = $page['title'];
        $data['meta_title'] = $page['meta_title'];
        $data['page'] = $page;

        $data['achieves'] = $this->common_model->get_data_array('cms_management', array('achieved' => 1, 'type' => 'Blog'));

        $this->load->view('frontend/aboutus', $data);
        // $this->output->enable_profiler(TRUE);

    }

    public function blogs()
    {
        $data = $this->data;
        $data['title'] = "Onsalenow blogs";
        $data['meta_title'] = "Onsalenow Blogs";
        $data['achieves'] = $this->common_model->get_data_array('cms_management', array('achieved' => 1, 'type' => 'Blog'));
        $data['blogs'] = $this->common_model->get_data_array('cms_management', array('type' => 'Blog', 'achieved' => 0));
        $this->load->view('frontend/blogs', $data);
    }

    public function contact_us()
    {
        $data = $this->data;
        $data['title'] = 'Contact Page';
        $data['countries'] = $this->front_model->get_data('ci_countries', ['status' => 1]);
        $data['contact_data'] = $this->common_model->get_single_data('cms_management', array('slug' => 'contact-us'));
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $arrayName = array(
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'theme' => $this->input->post('theme'),
                'location' => $this->input->post('location'),
                'company_name' => $this->input->post('company_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'information_required' => $this->input->post('information_required'),
                'message' => $this->input->post('message'),
            );
            $insertid = $this->common_model->add_data('ci_contact', $arrayName);
            if ($insertid) {
                $this->load->library('email');

                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1',
                    'newline' => '\r\n'
                );

                $message = "<p>Thank you for subscribing our newsletter. You will get the latest updates here!</p><br>";
                $message .= "<p>Thanks Team</p>";


                $this->email->initialize($config);
                $this->email->from('no-reply@gmail.com');
                $this->email->to($_POST['email']);
                $this->email->subject('Automated Response from onsalenow');
                $this->email->message($message);
                $this->email->send();

                $this->load->view('frontend/common/thankyou', $data);
            }
        } else {
            $this->session->set_flashdata('success', 'please check captcha');
            //redirect(base_url('home/contact_us'));
        }

        $this->load->view('frontend/contact_us', $data);
    }


    public function contact_form()
    {
        $data = $this->data;
        $data['title'] = 'Contact Page';
        if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            $arrayName = array(
                'name' => $this->input->post('name'),
                'address' => $this->input->post('address'),
                'theme' => $this->input->post('theme'),
                'location' => $this->input->post('location'),
                'company_name' => $this->input->post('company_name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'information_required' => $this->input->post('information_required'),
                'message' => $this->input->post('message'),
            );
            $insertid = $this->common_model->add_data('ci_contact', $arrayName);
            if ($insertid) {

                $this->load->library('email');

                $config = array(
                    'mailtype' => 'html',
                    'charset' => 'utf-8',
                    'priority' => '1',
                    'newline' => '\r\n'
                );

                $message = "<p>Thank you for subscribing our newsletter. You will get the latest updates here!</p><br>";
                $message .= "<p>Thanks Team</p>";


                $this->email->initialize($config);
                $this->email->from('no-reply@gmail.com');
                $this->email->to($_POST['email']);
                $this->email->subject('Automated Response from onsalenow');
                $this->email->message($message);
                $this->email->send();

                $this->load->view('frontend/common/thankyou', $data);
            }
        } else {
            $this->session->set_flashdata('success', 'please check captcha');
            redirect(base_url('home/contact_us'));
        }
    }


    public function error_404()
    {
        $data = $this->data;
        $data['baseUrl'] = base_url();
        $data['title'] = '404 Page not found';
        $this->load->view('frontend/layout/header', $data);
        $this->load->view('errors/html/error_404', $data);
        $this->load->view('frontend/layout/footer', $data);
    }

    public function category()
    {
        $data = $this->data;
        $where = array();
        $category = $this->uri->segment(2);
        // echo $category; die;
        $subcate = is_numeric($this->uri->segment(3)) ? '' : $this->uri->segment(3);
        if ($category == '') {
            $data['title'] = 'All Categories';
            $data['categoryList'] = $this->front_model->getAllCategoryList();

            $this->load->view('frontend/category/allCategory', $data);
            return false;
        }
        if ($subcate != '')
            $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));

        $data['metadetails'] = getMetaDetailsByCategorySlug($category);
        $category = $this->common_model->get_single_data('ci_category', array('slug' => $category));

        $subtitle = isset($subcategory['name']) ? " | " . $subcategory['name'] : '';
        $data['title'] = "Onsalenow | " . ucfirst($category['name']) . $subtitle;
        $data['page'] = $this->uri->segment(4) ? $this->uri->segment(4) : (is_numeric($this->uri->segment(3)) ? $this->uri->segment(3) : 1);
        $where['category_id'] = $category['id'];
        $data['category'] = $category;
        $data['subcategory'] = @$subcategory;
        $data['category_id'] = $category['id'];
        $data['subcategory_id'] = @$subcategory['id'];
        $data['subcategories'] = $this->front_model->get_data('ci_subcategory', $where);

        $data['brands'] = $this->front_model->getBrandByCate(@$category['id'], @$subcategory['id']);

        $data['discount'] = $this->front_model->getDiscount();

        $data['name'] = isset($subcategory['name']) ? $subcategory['name'] : $category['name'];
        // $data['image'] = isset($subcategory['image']) ? showImage($this->config->item('subcategory'), $subcategory['image']) : showImage($this->config->item('category'), $category['image']);
        $data['image'] = $category['image'];

        $data['description'] = isset($subcategory['description']) ? $subcategory['description'] : $category['description'];

        $this->load->view('frontend/category/index', $data);
        // $this->output->enable_profiler(TRUE);

    }

    public function getAllBrand()
    {
        //echo "<pre>"; print_r($_POST); die;

        $key = $this->input->post('text');
        $catid = $this->input->post('catid');
        $allbrand = $this->front_model->getBrandDataList([], 1, $key, $catid);
        $response = '';
        if (!empty($allbrand)) {
            foreach ($allbrand as $key => $value) {
                $count = chechProductsByBrand($catid, $value->id);

                if (!$count) {
                    continue;
                }

                $response .= '<label class="CustomCheck">' . $value->brand_name . '(' . $count . ')';
                $response .= '<input type="checkbox" name="brands" id="brands_' . $value->id . '" class="common_selector brands" value="' . $value->id . '"   onclick="getTopFIlter();">
                                                                            <span class="checkmark"></span>                    
                                                                            </label>';
            }
            echo $response;

        } else {
            return false;
        }
    }

    public function brand()
    {
        $data = $this->data;
        $where = array();
        $slug = $this->uri->segment(2);
        if (is_numeric($slug) || $slug == '') {

            $data['title'] = 'All Brands';
            $page = $slug == '' ? 1 : $slug;

            $alphabeticList = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            $data['alphabeticList'] = $alphabeticList;
            $data['brandList'] = $this->front_model->getAllBrandList();
            $data['metadetails'] = $this->products_model->get_meta_details($type = 'brand');
            $this->load->view('frontend/brand/all_brands_list', $data);
            return false;
        }
        $data['metadetails'] = getMetaDetailsByBrandSlug($slug);
        $brand = $this->front_model->getBrandData(array('ci_brand.slug' => $slug), 0);
        $data['title'] = "Onsalenow | " . ucfirst($brand['brand_name']);
        $where['category_id'] = $brand['category_id'];

        $category = $this->front_model->getBrandCate([$brand['id']]);
        $subcategories = $this->common_model->get_single_data('ci_subcategory', ["id" => $brand['sub_category_id']]);
        $data['page'] = $this->uri->segment(4) ? $this->uri->segment(4) : (is_numeric($this->uri->segment(3)) ? $this->uri->segment(3) : 1);
        $data['category'] = $category;
        $data['subcategories'] = @$subcategories;
        $data['category_id'] = @$category['id'];
        $data['subcategory_id'] = @$subcategories['id'];
        $data['brand_id'] = $brand['id'];
        $data['brand'] = $brand;
        $data['brands'] = $this->front_model->getBrandData(["ci_products.category_id" => $brand['category_id']]);

        $data['name'] = $brand['brand_name'];
        $data['image'] = $brand['image'];
        $data['discount'] = $this->front_model->getDiscount();
        $data['description'] = $brand['description'];

        $this->load->view('frontend/brand/index', $data);
        // $this->output->enable_profiler(TRUE);

    }

    public function add_newsletter()
    {
        $data = $this->data;
        $this->load->library('email');

        $config = array(
            'mailtype' => 'html',
            'charset' => 'utf-8',
            'priority' => '1',
            'newline' => '\r\n'
        );

        $message = "<p>Thank you for subscribing our newsletter. You will get the latest updates here!</p><br>";
        $message .= "<p>Thanks Team</p>";


        $this->email->initialize($config);
        $this->email->from('no-reply@gmail.com');
        $this->email->to($_POST['email']);
        $this->email->subject('Automated Response from onsalenow');
        $this->email->message($message);
        $this->email->send();

        $arrayName = array(
            'email' => $this->input->post('email'),
            'created_at' => date('Y-m-d H:i:s'),
        );
        $insertid = $this->common_model->add_data('ci_newsletter', $arrayName);
        $this->load->view('frontend/common/thankyou', $data);
    }

    public function getAllBrandsBySearchText()
    {
        $searchVal = trim($this->input->post('searchData'));
        $result = $this->front_model->getBrandListBySearch($searchVal);
    
        $response = '';
        $alphabeticList = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        foreach ($alphabeticList as $key => $alphabeticValue) {
            $brandFound = false;
            foreach ($result as $key => $value) {
                $brandValueFirst = substr($value['brand_name'], 0, 1);
                if ($brandValueFirst == $alphabeticValue) {
                    $brandFound = true;
                    break;
                }
            }
            if ($brandFound) {
                $response .= '<div class="title red-title"></div>';
                foreach ($result as $key => $value) {
                    $brandValueFirst = substr($value['brand_name'], 0, 1);
                    if ($brandValueFirst == $alphabeticValue) {
                        $response .= '<li><a href="' . $value['slug'] . '">' . $value['alias'] . '</a></li>';
                    }
                }
            }
        }
    
        echo $response;
    }
    


    public function filters()
    {
        $alphaval = $this->input->post('value');
        $result = $this->front_model->getBrandListByFilter($alphaval);
        if (empty($alphaval)) {
            $response = '';
            $alphabeticList = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
            foreach ($alphabeticList as $key => $alphabeticValue) {

                $response .= '<p class="title red-title">' . $alphabeticValue . '</p>';
                $before = '';
                foreach ($result as $key => $value) {
                    if ($value['totalProduct'] > 0) {

                        $brandValueFirst = substr($value['alias'], 0, 1);

                        if ($brandValueFirst == $alphabeticValue && $value['alias'] != $before) {

                            $response .= '<li><a href="' . $value['slug'] . '">' . $value['alias'] . '</a></li>';
                        }
                    }
                    $before = $value['alias'];
                }
            }
            echo $response;
        } else {
            $html = '';

            $html .= '<p class="title red-title">' . $alphaval . '</p>';
            $before = '';

            foreach ($result as $key => $value) {
                if ($value->alias != $before) {
                    $html .= '<li><a href="' . $value->slug . '">' . $value->alias . '</a></li>';
                }
                $before = $value->alias;
            }

            // echo "<pre>"; print_r($html);
            echo $html;
        }
    }
}