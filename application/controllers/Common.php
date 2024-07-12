<?php
//error_reporting(0);
defined('BASEPATH') or exit('No direct script access allowed');

class Common extends CI_Controller
{

    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('frontend/common_model');
        $this->load->model('frontend/front_model');
        $this->load->model('frontend/products_model');

        $this->load->library('pagination');

        $categorywithsub = $this->front_model->getCategorywithsub();
        $topcategory = $this->front_model->getTopCategoryList();
        $pages = $this->common_model->getCMSPages();
        $this->data = array(
            'categorywithsub' => $categorywithsub,
            'topcategory' => $topcategory,
            'pages' => $pages
        );

    }

    public function redirect()
    {
        $second_slug = $this->uri->segment(2);
        $slug_name = $this->uri->segment(1);
        if (!$second_slug) {
            $location = "";
            if ($slug_name == "top-products.html")
                $location = "products/products_list";
            header("Location: /$location");
        } else {
            if ($slug_name == "Sports")
                $slug_name = "Sports-Fitness";
            $res = $this->front_model->get_category_by_slug($slug_name);
            if ($res) {
                header("Location: /$slug_name");
            } else {
                header("Location: /");
            }
        }
        exit;
    }

    public function category()
    {
        $data = $this->data;
        $where = array();
        $slug_name = $this->uri->segment(1);
        $category = $slug_name;
        $subcate = is_numeric($this->uri->segment(2)) ? '' : $this->uri->segment(2);
        // echo $subcate; die;
        $q = $this->common_model->checkSlugExsits($subcate);
        if ($q == 3) {

            // if($category == ''){
            //     // echo "cate";
            //     $data['title'] = 'All Categories';
            //     $this->load->view('frontend/category/allCategory', $data);
            //     return false;
            // }
            if ($subcate != '') {
                $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));
            }
           
            $data['metadetails'] = getMetaDetailsByCategorySlug($category);
            $category = $this->common_model->get_single_data('ci_category', array('slug' => $category));
            $subtitle = isset($subcategory['name']) ? " | " . $subcategory['name'] : '';
            $data['title'] = "Onsalenow | " . ucfirst(@$category['name']) . @$subtitle;
            $data['page'] = $this->uri->segment(2) ? $this->uri->segment(2) : (is_numeric($this->uri->segment(1)) ? $this->uri->segment(1) : 0);
            $where['category_id'] = $category['id'];
            $data['category'] = $category;
            $data['subcategory'] = @$subcategory;
            $data['category_id'] = $category['id'];
            // $data['category_id'] = $category['id'];
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

    private function pro_list()
    {
        $data = $this->data;
        $where = array();
        $data['title'] = 'Top 50 Products';
        if (@$search) {
            $where['ci_products.name'] = @$search;
        }

        $data['products'] = $this->front_model->get_top_products($limit = 50, $where);
        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];

        $this->load->view('frontend/products/products_list', $data);
    }

    public function index()
    {
        if(stripos($this->uri->segment(1), ".html") !== false || stripos($this->uri->segment(2), ".html") !== false) {
            show_404();
        }

        $slug_name = $this->uri->segment(1);
        $slug_name2 = $this->uri->segment(2);
        $currentURL = current_url();

        $params = $_SERVER['QUERY_STRING'];
        // echo $params; die;
        $q = $this->common_model->checkSlugExsits($slug_name, $slug_name2);
        // echo $q; die;
        if ($q == 1) {
            // echo 'cat'; die;
            $data = $this->data;
            $where = array();
            $related_where = array();
            $data['title'] = 'Product Details';
            // $slug = $this->uri->segment(1);
            $where['slug'] = $slug_name2;
            // echo $slug;die;
            $data['products'] = $this->common_model->get_product($slug_name2);
            if (empty($data['products'])) {
                header("Location: /$slug_name");
                exit;
            }

            $id = $data['products']['id'];

            // Top deals count
            $this->common_model->update_product_visit_count($id, $data['products']['category_id']);

            // Related products via category and sub category
            $related_where['ci_products.category_id'] = $data['products']['category_id'];
            $related_where['ci_products.subCategory_id'] = $data['products']['subCategory_id'];
            $data['related_product'] = $this->products_model->get_all_related_products($related_where, 'RANDOM');
            //echo "<pre>";
            //print_r($data['related_product']); die;

            $data['price_comparison'] = $this->products_model->get_option_merchant_products($data['products']['name_wp']);

            $productDetail = $this->products_model->get_master_product($id);
            $data['productdetail'] = $productDetail;

            $merchantdata = $this->products_model->get_merchant_products($id);

            $data['merchant_data'] = $merchantdata;

            $metadetails = $this->products_model->get_meta_details($type = 'product');

            $findme = array();
            $findme = ["<product_name>", "<brand_name>", "<category_name>", "<subcategory_name>", "<merchant_name>"];
            $metaTitle = $metadetails->meta_title;
            $metaDescription = $metadetails->meta_description;
            $metaTag = $metadetails->meta_tag;

            $actualData = array();
            $actualData = [$productDetail->name, $productDetail->brand_name, $productDetail->categoryname, $productDetail->subcategorynam, $merchantdata->merchant_name];

            $newPhrase = str_replace($findme, $actualData, $metaTitle);
            $data['meta_title'] = $newPhrase;
            $data['meta_h1'] = str_replace($findme, $actualData, $metadetails->meta_h1);

            $metaDesc = str_replace($findme, $actualData, $metaDescription);
            $data['meta_description'] = $metaDesc;

            $metatags = str_replace($findme, $actualData, $metaTag);
            $data['meta_tag'] = $metatags;

            // $data['metadetails'] = $this->products_model->get_meta_details($type='product');

            $data['productImages'] = $this->front_model->get_data('ci_product_images', ['product_id' => $id]);

            $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];

            // load price history
            $rawHistory = loadPriceHistory(intval($productDetail->merchante_top_id));

            $startDate = strtotime('-1 year');
            $endDate = strtotime('tomorrow');

            $historyDateArray = array();
            $sellingPriceArray = array();
            $costPriceArray = array();

            while ($startDate <= $endDate) {
                $historyDateArray[] = date('d/m/Y', $startDate);

                $sellingPrice = '';
                $costPrice = '';
                foreach ($rawHistory as $row) {
                    if ($row['history_date'] == date('Y-m-d', $startDate)) {
                        $sellingPrice = $row['selling_price'];
                        $costPrice = $row['cost_price'];
                        break;
                    }
                }

                $sellingPriceArray[] = $sellingPrice;
                $costPriceArray[] = $costPrice;
                $startDate = strtotime('+1 day', $startDate);
            }

            $data['historyDateArray'] = $historyDateArray;
            $data['sellingPriceArray'] = $sellingPriceArray;
            $data['costPriceArray'] = $costPriceArray;
            $data['priceDrop'] = $this->products_model->getPriceDrop($id, $merchantdata->merchante_top_id);
            $this->load->view('frontend/products/product_detail', $data);
        } else if ($q == 2) {
            // echo "brand"; die;
            $data = $this->data;
            $where = array();
            $slug = $this->uri->segment(1);

            $brandId = $this->front_model->getBrandId($slug);
            // echo "<pre>"; print_r($brandId); die;

            // All Brand //
            // echo $slug; die;

            if ((is_numeric($slug) && !$brandId) || $slug == '') {
                $data['title'] = 'All Brands';
                $page = $slug == '' ? 1 : $slug;
                // dd($slug);
                $conditions['returnType'] = 'count';
                $totalRec = $this->front_model->getAllBrands($conditions);

                $this->load->library('pagination');
                // Pagination configuration
                $config['base_url'] = base_url('brand/');
                $config['uri_segment'] = 1;
                $config['total_rows'] = $totalRec;
                $config['per_page'] = 24;
                $config['use_page_numbers'] = true;
                $config['full_tag_open'] = '<ul class="pagination">';
                $config['full_tag_close'] = '</ul>';
                $config['first_tag_open'] = '<li>';
                $config['first_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li>';
                $config['last_tag_close'] = '</li>';
                $config['next_link'] = '&gt;';
                $config['next_tag_open'] = '<li class="getPage">';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '&lt;';
                $config['prev_tag_open'] = '<li>';
                $config['prev_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='active getPage'><a href='" . base_url('brand/') . "'>";
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="getPage">';
                $config['num_tag_close'] = '</li>';
                $config['num_links'] = 3;

                $this->pagination->initialize($config);
                // $data['pagination'] = $this->pagination->create_links();

                // Define offset
                $page = $page;
                $offset = !$page ? 0 : $page;

                $conditions = array(
                    'start' => $offset,
                    'page' => $page,
                    'limit' => 24,
                );
                $data['brands'] = $this->front_model->getAllBrands($conditions);
                $this->load->view('frontend/brand/allBrands', $data);
                // $this->output->enable_profiler(TRUE);

                return false;

            }

            $brand = $this->front_model->getBrandData(array('ci_brand.slug' => $slug), 0);
            if (!empty($brand)) {
                $data['title'] = "Onsalenow | " . ucfirst($brand['brand_name']);
            } else {
                $data['title'] = "Onsalenow | ";
            }

            $where['category_id'] = $brand['category_id'];

            $brandIdList = $this->front_model->getBrandIdListFromSlug($slug);

            $category = $this->front_model->getBrandCate($brandIdList);

            $subcategories = $this->common_model->get_single_data('ci_subcategory', ["id" => $brand['sub_category_id']]);

            if (@$_GET['min_price'] && @$_GET['max_price']) {
                $allsubcategory = $this->front_model->getBrandSubCateBySlugAndPrice($brand['slug'], @$_GET['min_price'], @$_GET['max_price'], @$_GET['from_discount']);
            } else {
                $allsubcategory = $this->front_model->getBrandSubCateBySlug($brand['slug']);
            }

            // print_r($allsubcategory); die;

            $data['page'] = $this->uri->segment(4) ? $this->uri->segment(4) : (is_numeric($this->uri->segment(3)) ? $this->uri->segment(3) : 1);
            $data['category'] = $category;
            $data['allsubcategory'] = $allsubcategory;
            $data['subcategories'] = @$subcategories;
            $data['category_id'] = @$category['id'];
            $data['subcategory_id'] = @$subcategories['id'];
            $data['cat_slug'] = @$category['category_slug'];
            $data['brand_id'] = $brand['id'];
            $data['brand'] = $brand;
            $data['brands'] = $this->front_model->getBrandData(["ci_products.category_id" => $brand['category_id']]);
            $subcategory_from_array = @$_GET['subcategory'] ? explode(',', @$_GET['subcategory']) : '';
            if ($subcategory_from_array == '') {
                $subcategory_from_array = [];
                $subcategory_from_array[] = @$category['category_slug'];
            }

            $data['minmaxprice'] = getMinMaxPrice(@$_GET['category'] ? explode(',', @$_GET['category']) : '', $subcategory_from_array, $brandIdList, @$_GET['from_discount'], @$_GET['to_discount'], @$_GET['In_Stock']);

            // $data['name'] = $brand['brand_name'];
            $data['name'] = (isset($brand['alias']) && $brand['alias'] != '') ? $brand['alias'] : $brand['brand_name'];
            $data['image'] = $brand['image'];

            if (@$_GET['min_price']) {
                $categoryList = explode(',', @$_GET['category']);
                $subcatid = explode(',', @$_GET['subcategory']);
                $data['discount'] = $this->front_model->getDiscount_with_subcategory($brandIdList, $categoryList, $subcatid, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
            } else {
                $data['discount'] = $this->front_model->getDiscountByBrandIdList($brandIdList);
            }

            $data['description'] = $brand['description'];

            $metadetails = $this->products_model->get_meta_details($type = 'brand');
            if (!empty($_GET['brand'])) {
                $brand = $this->front_model->getBrandData(array('ci_brand.slug' => $slug), 0);

                $brandId = $_GET['brand'];
                $categoryList = explode(',', @$_GET['category']);
                $subcatid = explode(',', @$_GET['subcategory']);
                $min_price = $_GET['min_price'];
                $max_price = $_GET['max_price'];
                $sort_by = $_GET['sort_by'];
                $In_Stock = $_GET['In_Stock'];
                $page = $this->uri->segment(2);

                $filter_array = array(
                    "categoryList" => $categoryList,
                    "subcatid" => $subcatid,
                    "min_price" => $min_price,
                    "max_price" => $max_price,
                    "from_discount" => @$_GET['from_discount'],
                    "to_discount" => @$_GET['to_discount'],
                    "sort_by" => $sort_by,
                    "brandSlug" => $slug,
                    "page" => $page,
                    "In_Stock" => $In_Stock,
                );

                $filterDataCount = $this->products_model->getFilterDataByBrandSearchCount($filter_array);
                $filterData = $this->products_model->getFilterDataByBrandSearch($filter_array, $filterDataCount);

                $per_page = 12;
                $paginationUrl = base_url() . $slug;

                $config = array();
                $config['base_url'] = $paginationUrl;
                $config['total_rows'] = $filterDataCount;
                $config['per_page'] = $per_page;
                $config['uri_segment'] = 2;
                $config['use_page_numbers'] = true;
                $config['full_tag_open'] = '<ul class="pagination">';
                $config['full_tag_close'] = '</ul>';
                $config['first_tag_open'] = '<li class="getPage">';
                $config['first_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li class="getPage">';
                $config['last_tag_close'] = '</li>';
                $config['next_link'] = '&gt;';
                $config['next_tag_open'] = '<li class="getPage">';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '&lt;';
                $config['prev_tag_open'] = '<li class="getPage">';
                $config['prev_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="getPage">';
                $config['num_tag_close'] = '</li>';
                $config['num_links'] = 3;
                $config['reuse_query_string'] = true;
                $this->pagination->initialize($config);
                $data['brandData'] = $filterData;
                $data['pagination'] = $this->pagination->create_links();

                $data['categoryList'] = $categoryList;
                $data['brandId'] = $brandId;
                $data['filter_min_price'] = $min_price;
                $data['filter_max_price'] = $max_price;
                $categoryListData = $this->products_model->getCategoryList($categoryList);
                $data['categoryListData'] = $categoryListData;
                $subcategoryListData = $this->products_model->getSubcategoriesName($subcatid);
                $data['subcategoryList'] = $subcatid;
                $data['subcategoryListData'] = $subcategoryListData;
            } else {
                $brand = $this->front_model->getBrandData(array('ci_brand.slug' => $slug), 0);

                $result = $this->products_model->getProducts($subcatid = "", $brand['slug'], 'brand');
                $count = $this->products_model->getProductsCounts($subcatid = "", $brand['slug'], 'brand');

                $per_page = 12;
                $paginationUrl = base_url() . $slug;

                $config = array();
                $config['base_url'] = $paginationUrl;
                $config['total_rows'] = $count;
                $config['per_page'] = $per_page;
                $config['uri_segment'] = 2;
                $config['use_page_numbers'] = true;
                $config['full_tag_open'] = '<ul class="pagination">';
                $config['full_tag_close'] = '</ul>';
                $config['first_tag_open'] = '<li class="getPage">';
                $config['first_tag_close'] = '</li>';
                $config['last_tag_open'] = '<li class="getPage">';
                $config['last_tag_close'] = '</li>';
                $config['next_link'] = '&gt;';
                $config['next_tag_open'] = '<li class="getPage">';
                $config['next_tag_close'] = '</li>';
                $config['prev_link'] = '&lt;';
                $config['prev_tag_open'] = '<li class="getPage">';
                $config['prev_tag_close'] = '</li>';
                $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                $config['cur_tag_close'] = '</a></li>';
                $config['num_tag_open'] = '<li class="getPage">';
                $config['num_tag_close'] = '</li>';
                $config['num_links'] = 3;
                $this->pagination->initialize($config);
                $data['brandData'] = $result;

                $data['pagination'] = $this->pagination->create_links();
            }

            $secondLastKey = count($this->uri->segment_array()) - 1;
            $data['last_uri_seg'] = $this->uri->segment($secondLastKey);
            $data['current_url'] = $currentURL;
            $data['param'] = $params;
            $findme = array();
            $findme = ["<brand_name>", "<category_name>", "<subcategory_name>"];
            $metaTitle = $metadetails->meta_title;
            $metaDescription = $metadetails->meta_description;
            $metaTag = $metadetails->meta_tag;

            $actualData = array();
            $actualData = [$brand['alias'], $category[0]->name, $subcategories['name']];

            $newPhrase = str_replace($findme, $actualData, $metaTitle);
            $data['meta_title'] = $newPhrase;

            $metaDesc = str_replace($findme, $actualData, $metaDescription);
            $data['meta_description'] = $metaDesc;

            $metatags = str_replace($findme, $actualData, $metaTag);
            $data['meta_tag'] = $metatags;
            $data['meta_h1'] = str_replace($findme, $actualData, $metadetails->meta_h1);

            $fromDiscount = @$_GET['from_discount'];
            $toDiscount = @$_GET['to_discount'];

            if ($fromDiscount !== null && $fromDiscount !== '' && $fromDiscount !== 'null') {
                if (floatval($fromDiscount) > floatval($data['discount']->percent_discount)) {
                    $fromDiscount = $data['discount']->percent_discount;
                }
            } else {
                $fromDiscount = 0;
            }

            if ($toDiscount != null && $toDiscount != '' && $toDiscount != 'null') {
                if (floatval($toDiscount) > floatval($data['discount']->percent_discount)) {
                    $toDiscount = ceil(floatval($data['discount']->percent_discount));
                }
            } else {
                $toDiscount = ceil(floatval($data['discount']->percent_discount));
            }

            $data['fromToDiscount'] = [$fromDiscount, $toDiscount];
            $params = $_SERVER['QUERY_STRING'];
            $count = count($data['brandData']);
            if ($count <= 0) {
                $data['products'] = $this->front_model->get_top_products($limit = 50, []);
            }else log_message("debug", "count: $count");
            $this->load->view('frontend/brand/index', $data);
            // $this->output->enable_profiler(TRUE);

        } else if ($q == 3) {

            $data = $this->data;
            $where = array();
            
            
            $category = $this->uri->segment(1);
            $categorySlug = $this->uri->segment(1);
            
            $subcate = is_numeric($this->uri->segment(2)) ? '' : $this->uri->segment(2);
            $subCategorySlug = is_numeric($this->uri->segment(2)) ? '' : $this->uri->segment(2);
            
            if ($category == '') {
                $data['title'] = 'All Categories';
                $this->load->view('frontend/category/allCategory', $data);
                return false;
            }
            
            if ($subcate != '') {
                $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));
            }
            
            $category = $this->common_model->get_single_data('ci_category', array('slug' => $category));
            $subtitle = isset($subcategory['name']) ? " | " . $subcategory['name'] : '';
            $data['title'] = "Onsalenow | " . ucfirst(@$category['name']) . @$subtitle;
            $data['page'] = $this->uri->segment(3) ? $this->uri->segment(3) : (is_numeric($this->uri->segment(2)) ? $this->uri->segment(2) : 1);
            @$where['category_id'] = @$category['id'];
            $data['category'] = $category;
            $data['cat_slug'] = $category['slug'];
            $data['subcategory'] = @$subcategory;
            @$data['category_id'] = @$category['id'];
            $data['subcategory_id'] = @$subcategory['id'];
            if (@$data['category_id']==24) {
                $where = array(
                    'category_id' => '24',
                     '(name LIKE \'Men%\' OR name LIKE \'Women%\' OR name LIKE \'Unisex%\')' => null
                     
                 );
                 $subcategories = $this->front_model->get_data('ci_subcategory', $where);
            }
            $subcategories = $this->front_model->get_data('ci_subcategory', $where);
            // dd($subcategories);
            $subcategory_from_array = @$_GET['subcategory'] ? explode(',', @$_GET['subcategory']) : '';
            if ($subcategory_from_array == '') {
                $subcategory_from_array = [];
                $subcategory_from_array[] = @$subcategory['id'];
            }

            $data['minmaxprice'] = getMinMaxPrice([$category['id']], $subcategory_from_array, @$_GET['brand'] ? explode(',', @$_GET['brand']) : '', @$_GET['from_discount'], @$_GET['to_discount'], @$_GET['In_Stock']);

            $data['subCategorySlug'] = $subCategorySlug;

            $metaSubCat = '';
            $count = 0;
            foreach ($subcategories as $subCatVal) {

                if ($count > 0) {
                    $metaSubCat .= ', ';
                }

                $metaSubCat .= $subCatVal->name;
                $count++;
            }

            $data['subcategories'] = $this->front_model->get_data('ci_subcategory', $where);

            $brands = $this->front_model->getBrandByCate(@$category['id'], $this->input->get('subcategory'), 1, @$_GET['min_price'], @$_GET['max_price'], @$_GET['from_discount'], @$_GET['to_discount']);

            $data['brands'] = $brands;

            if ($subcate != '') {
                if (@$_GET['min_price']) {
                    $brand = explode(',', @$_GET['brand']);
                    $subcategory = explode(',', @$_GET['subcategory']);
                    $data['discount'] = $this->front_model->getDiscountBySubCategory_withbrand($category['id'], $subcategory, 'sub-category', $brand, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
                } else {
                    $data['discount'] = $this->front_model->getDiscountBySubCategory($category['id'], $subcategory['id'], 'sub-category');
                }
            } else {
                if (@$_GET['min_price']) {
                    $brand = explode(',', @$_GET['brand']);
                    $subcategory = explode(',', @$_GET['subcategory']);
                    $data['discount'] = $this->front_model->getDiscountBySubCategory_withbrand($category['id'], $subcategory, 'category', $brand, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
                } else {
                    $data['discount'] = $this->front_model->getDiscountByCategory($category['id'], 'category');
                }
            }

            @$data['name'] = isset($subcategory['name']) ? @$subcategory['name'] : @$category['name'];
            @$data['image'] = @$category['image'];

            $secondLastKey = count($this->uri->segment_array()) - 1;
            $data['last_uri_seg'] = $this->uri->segment($secondLastKey);
            $data['current_url'] = $currentURL;
            $data['params'] = $params;

            @$data['description'] = isset($subcategory['description']) ? @$subcategory['description'] : @$category['description'];

            if (!empty($_GET['category'])) {
                $brandList = explode(',', @$_GET['brand']);
                $catid = @$_GET['category'];
                $subcatid = (@$_GET['subcategory'] == null || @$_GET['subcategory'] == '') ? [] : explode(',', @$_GET['subcategory']);
                $min_price = @$_GET['min_price'];
                $max_price = @$_GET['max_price'];
                $sort_by = @$_GET['sort_by'];
                $In_Stock = @$_GET['In_Stock'];

                if ($subcate != '') {

                    $page = $this->uri->segment(3);
                    //  echo $page; die;
                } else {

                    $page = $this->uri->segment(2);
                }

                $filter_array = array(
                    "from_discount" => @$_GET['from_discount'],
                    "to_discount" => @$_GET['to_discount'],
                    "catid" => $catid,
                    "subcatid" => $subcatid,
                    "min_price" => $min_price,
                    "max_price" => $max_price,
                    "sort_by" => $sort_by,
                    "In_Stock" => $In_Stock,
                    "brandList" => $brandList,
                    "page" => $page
                );

                if (!empty($subcatid)) {
                    if (@$_GET['min_price']) {
                        $brand = explode(',', @$_GET['brand']);
                        $subcategory = explode(',', @$_GET['subcategory']);
                        $data['discount'] = $this->front_model->getDiscountBySubCategory_withbrand($catid, $subcategory, 'sub-category', $brand, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
                    } else {
                        $data['discount'] = $this->front_model->getDiscountBySubCategory($catid, $subcategory['id'], 'sub-category');
                    }
                } else {
                    if (@$_GET['min_price']) {
                        $brand = explode(',', @$_GET['brand']);
                        $subcategory = explode(',', @$_GET['subcategory']);
                        $data['discount'] = $this->front_model->getDiscountBySubCategory_withbrand($catid, $subcategory, 'category', $brand, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
                    } else {
                        $data['discount'] = $this->front_model->getDiscountByCategory($catid, 'category');
                    }
                }

                $filterDataCount = $this->products_model->getFilterDataCount($filter_array);

                $filterData = $this->products_model->getFilterData($filter_array, $filterDataCount);

                $brandListData = $this->products_model->getFilterBrandName($brandList);

                $data['brandListData'] = $brandListData;
                if (!empty($subcatid) && !is_numeric($this->uri->segment(2))) {
                    $subcategoryListData = $this->products_model->getFilterSubcategoryName($subcatid);
                    $data['subcategoryListData'] = $subcategoryListData;

                    $per_page = 12;
                    $paginationUrl = base_url() . $categorySlug . '/' . $subCategorySlug;
                    $pagination = (floor($filterDataCount / $per_page));

                    $config = array();
                    $config['base_url'] = $paginationUrl;
                    $config['total_rows'] = $filterDataCount;
                    $config['per_page'] = $per_page;
                    $config['uri_segment'] = 3;
                    $config['use_page_numbers'] = true;
                    $config['full_tag_open'] = '<ul class="pagination">';
                    $config['full_tag_close'] = '</ul>';
                    $config['first_tag_open'] = '<li class="getPage">';
                    $config['first_tag_close'] = '</li>';
                    $config['last_tag_open'] = '<li class="getPage">';
                    $config['last_tag_close'] = '</li>';
                    $config['next_link'] = '&gt;';
                    $config['next_tag_open'] = '<li class="getPage">';
                    $config['next_tag_close'] = '</li>';
                    $config['prev_link'] = '&lt;';
                    $config['prev_tag_open'] = '<li class="getPage">';
                    $config['prev_tag_close'] = '</li>';
                    $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                    $config['cur_tag_close'] = '</a></li>';
                    $config['num_tag_open'] = '<li class="getPage">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 4;
                    $config['reuse_query_string'] = true;
                    $this->pagination->initialize($config);
                    $data['result'] = $filterData;
                    $data['pagination'] = $this->pagination->create_links();
                } else {
                    $per_page = 12;
                    $paginationUrl = base_url() . $categorySlug;
                    $pagination = floor($filterDataCount / $per_page);
                    $config = array();
                    $config['base_url'] = $paginationUrl;
                    $config['total_rows'] = $filterDataCount;
                    $config['per_page'] = $per_page;
                    $config['uri_segment'] = 2;
                    $config['use_page_numbers'] = true;
                    $config['full_tag_open'] = '<ul class="pagination">';
                    $config['full_tag_close'] = '</ul>';
                    $config['first_tag_open'] = '<li class="getPage">';
                    $config['first_tag_close'] = '</li>';
                    $config['last_tag_open'] = '<li class="getPage">';
                    $config['last_tag_close'] = '</li>';
                    $config['next_link'] = '&gt;';
                    $config['next_tag_open'] = '<li class="getPage">';
                    $config['next_tag_close'] = '</li>';
                    $config['prev_link'] = '&lt;';
                    $config['prev_tag_open'] = '<li class="getPage">';
                    $config['prev_tag_close'] = '</li>';
                    $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                    $config['cur_tag_close'] = '</a></li>';
                    $config['num_tag_open'] = '<li class="getPage">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 3;
                    $config['reuse_query_string'] = true;
                    $this->pagination->initialize($config);
                    $data['result'] = $filterData;
                    $data['pagination'] = $this->pagination->create_links();
                }

                $data['catid'] = $catid;
                $data['subcategoryList'] = @$subcatid;
                $data['brandList'] = @$brandList;
                $data['filter_min_price'] = $min_price;
                $data['filter_max_price'] = $max_price;
            } else {
                if ($subcate != '') {
                    $result = $this->products_model->getProducts($subcategory['id'], $category['id'], 'subcategory');
                    $count = $this->products_model->getProductsCounts($subcategory['id'], $category['id'], 'subcategory');

                    // echo $count; die;
                    $secondLastKey = count($this->uri->segment_array()) - 1;
                    $data['last_uri_seg'] = $this->uri->segment($secondLastKey);
                } else {
                    $result = $this->products_model->getProducts($subcatid = "", $category['id'], 'category');
                    $count = $this->products_model->getProductsCounts($subcatid = "", $category['id'], 'category');

                    //print_r($count);die;
                }

                /* Pagination */
                $per_page = 12;
                if ($subCategorySlug != '') {

                    // echo "subcat";die;
                    $paginationUrl = base_url() . $categorySlug . '/' . $subCategorySlug;

                    $pagination = (floor($count / $per_page));
                    //echo $pagination; die;
                    $config = array();
                    $config['base_url'] = $paginationUrl;
                    $config['total_rows'] = $count;
                    $config['per_page'] = $per_page;
                    $config['uri_segment'] = 3;
                    $config['use_page_numbers'] = true;
                    $config['full_tag_open'] = '<ul class="pagination">';
                    $config['full_tag_close'] = '</ul>';
                    $config['first_tag_open'] = '<li class="getPage">';
                    $config['first_tag_close'] = '</li>';
                    $config['last_tag_open'] = '<li class="getPage">';
                    $config['last_tag_close'] = '</li>';
                    $config['next_link'] = '&gt;';
                    $config['next_tag_open'] = '<li class="getPage">';
                    $config['next_tag_close'] = '</li>';
                    $config['prev_link'] = '&lt;';
                    $config['prev_tag_open'] = '<li class="getPage">';
                    $config['prev_tag_close'] = '</li>';
                    $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                    $config['cur_tag_close'] = '</a></li>';
                    $config['num_tag_open'] = '<li class="getPage">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_links'] = 4;
                    $this->pagination->initialize($config);
                    // echo $this->pagination->create_links()."pagination"; die;
                    $data['result'] = $result;

                    $data['pagination'] = $this->pagination->create_links();
                } else {
                    $paginationUrl = base_url() . $categorySlug;

                    $pagination = 13194;
                    //echo $pagination; die();
                    $config = array();
                    $config['base_url'] = $paginationUrl;
                    $config['total_rows'] = $count;
                    $config['per_page'] = $per_page;
                    $config['uri_segment'] = 2;
                    $config['use_page_numbers'] = true;
                    $config['full_tag_open'] = '<ul class="pagination">';
                    $config['full_tag_close'] = '</ul>';
                    $config['first_tag_open'] = '<li class="getPage">';
                    $config['first_tag_close'] = '</li>';
                    $config['last_tag_open'] = '<li class="getPage">';
                    $config['last_tag_close'] = '</li>';
                    $config['next_link'] = '&gt;';
                    $config['next_tag_open'] = '<li class="getPage">';
                    $config['next_tag_close'] = '</li>';
                    $config['prev_link'] = '&lt;';
                    $config['prev_tag_open'] = '<li class="getPage">';
                    $config['prev_tag_close'] = '</li>';
                    $config['cur_tag_open'] = "<li class='active getPage'><a href='" . $paginationUrl . "'>";
                    $config['cur_tag_close'] = '</a></li>';
                    $config['num_tag_open'] = '<li class="getPage">';
                    $config['num_tag_close'] = '</li>';
                    $config['num_link   s'] = 3;
                    $this->pagination->initialize($config);

                    $data['result'] = $result;
                    $data['pagination'] = $this->pagination->create_links();
                }
            }

            if ($subcate != '') {
                $actualData = array();
                $actualData = [$category['name'], @$subcategory['name']];
                $metadetails = $this->products_model->get_meta_details($type = 'sub_category');
            } else {

                $actualData = array();
                $actualData = [$category['name'], $metaSubCat];
                $metadetails = $this->products_model->get_meta_details($type = 'category');
            }
            $data['meta_h1'] = $metadetails->meta_h1;

            $findme = ["<category_name>", "<subcategory_name>"];
            $metaTitle = $metadetails->meta_title;
            $metaDescription = $metadetails->meta_description;
            $metaTag = $metadetails->meta_tag;

            $newPhrase = str_replace($findme, $actualData, $metaTitle);

            $data['meta_title'] = $newPhrase;

            $metaDesc = str_replace($findme, $actualData, $metaDescription);
            $data['meta_description'] = $metaDesc;

            $metatags = str_replace($findme, $actualData, $metaTag);
            $data['meta_tag'] = $metatags;

            $fromDiscount = @$_GET['from_discount'];

            $toDiscount = @$_GET['to_discount'];

            if ($fromDiscount !== null && $fromDiscount !== '' && $fromDiscount !== 'null') {
                if (floatval($fromDiscount) > floatval($data['discount']->percent_discount)) {
                    $fromDiscount = $data['discount']->percent_discount;
                }
            } else {
                $fromDiscount = 0;
            }

            if ($toDiscount != null && $toDiscount != '' && $toDiscount != 'null') {
                if (floatval($toDiscount) > floatval($data['discount']->percent_discount)) {
                    $toDiscount = ceil(floatval($data['discount']->percent_discount));
                }
            } else {
                $toDiscount = ceil(floatval($data['discount']->percent_discount));
            }

            $data['fromToDiscount'] = [$fromDiscount, $toDiscount];
            $count = count($data['result']);
            if ($count <= 0) {
                $data['products'] = $this->front_model->get_top_products($limit = 50, []);
            }
            $this->load->view('frontend/category/index', $data);
        } else if ($q == 4) {
            $this->pro_list();
        }
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
        if ($subcate != '') {
            $subcategory = $this->common_model->get_single_data('ci_subcategory', array('slug' => $subcate));
        }

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
