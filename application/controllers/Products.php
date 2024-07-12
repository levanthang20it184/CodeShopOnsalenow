<?php
ini_set('memory_limit', '8192M');
defined('BASEPATH') or exit('No direct script access allowed');

class Products extends CI_Controller
{
    var $data;

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

    public function index()
    {
        $data = $this->data;
        $where = array();
        $related_where = array();
        $data['title'] = 'Product Details';
        $slug = $this->uri->segment(2);
        $where['slug'] = $slug;
        // echo $slug;die;
        $data['products'] = $this->common_model->get_single_data('ci_products', $where);

        if (empty($data['products'])) {
            abort();
        }
        $id = $data['products']['id'];

        // Top deals count
        $product_visit = $this->common_model->get_single_data('product_visit', ['product_id' => $id]);
        $count = isset($product_visit['visit_count']) ? $product_visit['visit_count'] + 1 : 1;
        $this->products_model->update_data('product_visit', ['product_id' => $id], ['product_id' => $id, 'visit_count' => $count]);

        // Related products via category and sub category
        $related_where['ci_products.category_id'] = $data['products']['category_id'];
        $related_where['ci_products.subCategory_id'] = $data['products']['subCategory_id'];
        // $related_where['ci_products.status'] = 1;
        $data['related_product'] = $this->products_model->get_all_related_products($related_where, 'RANDOM');

        $data['price_comparison'] = $this->products_model->get_merchant_products($id, 'yes');

        $data['productdetail'] = $this->products_model->get_product_by_id($id);
        //print_r($data['productdetail']); die;

        $data['merchant_data'] = $this->products_model->get_merchant_products($id);

        $data['productImages'] = $this->front_model->get_data('ci_product_images', ['product_id' => $id]);

        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        //echo '<pre>'; print_r($data); die;

        $this->load->view('frontend/products/product_detail', $data);
    }


    public function products_list($search = '')
    {
        // echo "hi"; die;
        $data = $this->data;
        $category = $this->uri->segment(3);
        $where = array();
        $data['title'] = 'Top 50 Products';
        if ($search) {
            if ($search == "Men" || $search == "Unisex" || $search == "Women")
                $where['ci_subcategory.name'] = $search;
            else
                $where['ci_category.slug'] = $search;
        }
        $data['slug'] = $category;
        $data['products'] = $this->front_model->get_top_products($limit = 50, $where);
        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        //echo "<pre>";print_r($data);die;
        // qry();
        $this->load->view('frontend/products/products_list', $data);


    }

    public function products_bigsale($search = '')
    {
        $data = $this->data;
        $where = array();
        $filter = array();
        $data['title'] = 'Save 50-90%';
        
        if ($search) {
            $where['name'] = $search;
        }
    
        $brand_list = $_GET['brands'] ?? '';
        $category_list = $_GET['categories'] ?? '';
        $data['brandList'] = explode(",", $brand_list);
        $data['categoryList'] = explode(",", $category_list);
        $filter = [
            'brands' => $data['brandList'],
            'categories' => $data['categoryList'],
        ];
    
        $per_page = 16;
        $sort_by = $this->input->get('sort_by');
        $In_Stock = true;
        $data['fromToDiscount'] = [$_GET['minDiscount'] ?? 50, $_GET['maxDiscount'] ?? 99];        
        $data['fromToPrice'] = [$_GET['minPrice'] ?? null, $_GET['maxPrice'] ?? null];
    
        // Gọi phương thức để lấy tổng số sản phẩm
        $count_res = $this->front_model->get_bigsale_products_count($where, $data['fromToDiscount'], $In_Stock, $filter, $data['fromToPrice']);
        $filterDataCount = $count_res[0];
        $data['minMaxPercent'] = [$count_res[1], $count_res[2]];
        $data['minMaxPrice'] = [$count_res[3], $count_res[4], $count_res[5], $count_res[6]];
    
        // Gọi phương thức để lấy sản phẩm
        $res = $this->front_model->get_bigsale_products($per_page, $where, $data['fromToDiscount'], $In_Stock, $sort_by, $filter, $filterDataCount, $data['fromToPrice']);
    
        $data['products'] = $res[0];
        $data['brands'] = $res[1];
        $data['categories'] = $res[2];
        $data['subCategories'] = $res[3];
        $data['current_url'] = current_url();
    
        // Cấu hình phân trang
        $paginationUrl = base_url() . 'products/products_bigsale';
        $config = array();
        $config['base_url'] = $paginationUrl;
        $config['total_rows'] = $filterDataCount;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
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
        $data['pagination'] = $this->pagination->create_links();
    
        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        $this->load->view('frontend/products/products_bigsale', $data);
    }
    
    

    public function products_new_discounts($search = '')
    {
        $data = $this->data;
        $where = array();
        $data['title'] = 'New Discounts This Week';
        if ($search)
            $where['ci_products.name'] = $search;

        $per_page = 16;
        $sort_by = $this->input->get('sort_by');
        $In_Stock = true;
        $where['brands'] = $_GET['brands']??'';
        $where['categories'] = $_GET['categories']??'';
        $data['brandList'] = explode(",", $where['brands']);
        $data['categoryList'] = explode(",", $where['categories']);
        $data['fromToDiscount'] = [$_GET['minDiscount']??0, $_GET['maxDiscount']??100];
        $data['fromToPrice'] = [$_GET['minPrice']??null, $_GET['maxPrice']??null];
        $data['current_url'] = current_url();

        $res = $this->front_model->get_this_week_discounts_products($limit = $per_page, $where, $In_Stock, $sort_by, $data['fromToDiscount'], $data['fromToPrice']);
        $data['products'] = $res[0];
        $filterDataCount = $res[1];
        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        $data['brands'] = $res[2];
        $data['categories'] = $res[3];
        $data['fromToDiscount'] = $res[4];
        $data['subCategories'] = $res[5];

        $paginationUrl = base_url() . 'products/products_discount_thisweek';
        $config = array();
        $config['base_url'] = $paginationUrl;
        $config['total_rows'] = $filterDataCount;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
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
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('frontend/products/products_new_discounts', $data);

    }

    public function search_list()
    {
        $like = array();
        $where = array();
        $search = $this->input->post('product');
        if (strlen(trim($search)) < 3) {
            echo "Search term must be at least 3 characters long.";
            return;
        }

        $like['ci_products.name'] = $search;
        $brand_like['alias'] = $search;
        $column = "slug, image, id, name";
        // $where['ci_products.status'] = '1';
        $products = $this->common_model->get_data_search('ci_products', $where, $like);
        //$brands = $this->common_model->get_searched_data('ci_brand', $where, $brand_like, $limit = 3);
        $brands = $this->common_model->get_brand_searched_data($brand_like);
        $categories = $this->common_model->get_searched_subcategory('ci_subcategory', $where, $like, $limit = 3);

        $categoriedata = $this->common_model->get_searched_category('ci_category', $where, $like, $limit = 3);
        $response = '';
        $response .= "<div class='left-side'>";
        $response .= "<h4 class='br-sec'>Brands<h4>";
        if (!empty($brands)) {
            foreach ($brands as $brand) {
                if ($brand['totalProduct'] > 0) {
                    $response .= "<a href='" . base_url() . $brand['slug'] . "'>" . $brand['alias'] . "</a>";
                }
            }
        }
        $response .= "<h4 class='ct-sec'>Categories<h4>";
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if ($category['totalProduct'] > 0) {
                    $response .= "<a href='" . base_url() . $category['category_slug'] . "/" . $category['slug'] . "'>" . $category['name'] . "</a>";
                }
            }
        }

        if (!empty($categoriedata)) {
            foreach ($categoriedata as $categorydata) {
                $response .= "<a href='" . base_url() . $categorydata['category_slug'] . "'>" . $categorydata['name'] . "</a>";
            }
        }

        $response .= "</div>";

        if (!empty($products)) {
            $response .= "<div class='right-side'>";

            $cnt = 0;
            foreach ($products as $product) {
                if ($product['selling_price'] != Null && number_format($product["selling_price"], 2) > 0.01 && $cnt < 3) {
                    $cnt++;
                    $response .= '<a href="' . base_url() . $product["category_slug"] . '/' . $product["slug"] . '">
                        <div class="ListItems">
                            <figure>
                                <img src="' . showImage($this->config->item('product'), $product["image"]) . '" width="50" height="50" alt="' . $product["name"] . '">
                            </figure>
                            <li class="SearchItem" pname="' . $product["name"] . ' ">
                                ' . $product["name"] . '
                                <span class="searchPrice"> <b>Price &euro;' . number_format($product["selling_price"], 2) . '</b></span>
                            </li>
                        </div>
                    </a>';
                }
            }

            $response .= '<div class="ListItems"> <a class="mainbtn srch_btn" href="' . base_url('products?search=') . $search . '">See All</a> </div>';
            $response .= "</div>";
        }

        echo $response;
    }


    public function topProducts()
    {
        $data = $this->data;
        $data['title'] = "Top Products";
        $data['products'] = $this->front_model->get_topdeals();

        $this->load->view('frontend/products/products_list', $data);

    }


    public function products_filter()
    {
        $data = $this->data;
        $data['title'] = 'Product List';

        $searchkey = $this->input->get('search');
        $categoryList = explode(',', @$_GET['category']);
        $subcategory = $this->input->get('subcategory');
        $brandList = explode(',', @$_GET['brand']);
        $min_price = $this->input->get('min_price');
        $max_price = $this->input->get('max_price');
        $sort_by = $this->input->get('sort_by');
        $In_Stock = $this->input->get('In_Stock');

        $page = $this->uri->segment(2);

        // if ($min_price != '' && $min_price != 'null') {
            $data['searchkey'] = $searchkey;
            $data['categoryList'] = $categoryList;
            $data['subcatid'] = $subcategory;
            $data['min_price'] = $min_price;
            $data['max_price'] = $max_price;
            $data['sort_by'] = $sort_by;
            $data['brandList'] = $brandList;
            $data['page'] = $page;
            $data['In_Stock'] = $In_Stock;
            $data['filter_min_price'] = $min_price;
            $data['filter_max_price'] = $max_price;
            $data['current_url'] = current_url();

            $data['minmaxprice'] = getMinMaxPrice(@$_GET['category'] ? explode(',', @$_GET['category']) : '', [$subcategory], $brandList, @$_GET['from_discount'], @$_GET['to_discount'], $In_Stock, $searchkey);

            $data['category'] = $this->front_model->getCategoryBySearchKey($searchkey, $brandList, $data['minmaxprice'], @$_GET['from_discount'], @$_GET['to_discount'], $In_Stock);

            if (@$_GET['min_price']) {
                $categoryList = explode(',', @$_GET['category']);
                $brand = explode(',', @$_GET['brand']);
                $data['discount'] = $this->front_model->getDiscountBySearch_with_subcategory($searchkey, $brand, $categoryList, $_GET['min_price'], $_GET['max_price'], $_GET['In_Stock']);
            } else {
                $data['discount'] = $this->front_model->getDiscountBySearch($searchkey);
            }

            $data['brands'] = $this->front_model->getBrandBySearchKey($searchkey, $categoryList, @$_GET['from_discount'], @$_GET['to_discount'], $In_Stock, $data['minmaxprice']);
            $categoryListData = $this->products_model->getCategoryList($categoryList);
            $data['categoryListData'] = $categoryListData;
            $brandListData = $this->products_model->getFilterBrandName($brandList);
            $data['brandListData'] = $brandListData;

            $filter_array = array(
                "searchkey" => $searchkey,
                "categoryList" => $categoryList,
                "subcategory" => $subcategory,
                "min_price" => floor(intval($min_price)),
                "max_price" => ceil(intval($max_price)),
                "from_discount" => @$_GET['from_discount'],
                "to_discount" => @$_GET['to_discount'],
                "sort_by" => $sort_by,
                "brandId" => $brandList,
                "page" => $page,
                'In_Stock' => $In_Stock
            );

            $filterDataCount = $this->products_model->getFilterDataBySearchKeyCount($filter_array);
            $filterData = $this->products_model->getFilterDataBySearchKey($filter_array, $filterDataCount);

            $per_page = 12;
            $paginationUrl = base_url() . 'products';
            $config = array();
            $config['base_url'] = $paginationUrl;
            $config['total_rows'] = $filterDataCount;
            $config['per_page'] = $per_page;
            $config['uri_segment'] = 2;
            $config['use_page_numbers'] = TRUE;
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
            $this->load->view('frontend/products/products_filter', $data);

    }

    public function products_filter_top()
    {

        $filterBy = $this->input->post('filter_by');
        // $min_price = $this->input->post('min_price');
        // $max_price = $this->input->post('max_price');
        // echo "<pre>"; print_r($filterBy);
        $categories = array();
        $subcateparent = array();
        $subcategories = array();
        $brands = array();

        foreach ($filterBy as $value) {
            if ($value['key'] == 'categories' && !empty($value['value']) && $value['value'] != '') {
                foreach ($value['value'] as $values) {
                    $categories[] = (int)$values;
                }
            } elseif ($value['key'] == 'sub_categories' && !empty($value['value']) && $value['value'] != '') {
                foreach ($value['value'] as $values) {

                    $subcateparent[] = (int)$values[0];
                    $subcategories[] = (int)$values[1];
                }
            } elseif ($value['key'] == 'brands' && !empty($value['value']) && $value['value'] != '') {
                foreach ($value['value'] as $values) {
                    $brands[] = $values;
                }
            }
        }

        if (!empty($categories)) {

            // $subCat =  $this->products_model->getSubcategoriesName($subcategories);
            $data['catList'] = $categories;
        }
        if (!empty($subcategories)) {

            $subCat = $this->products_model->getSubcategoriesName($subcategories);
            $data['subCatList'] = $subCat;
        }
        if (!empty($brands)) {
            $brands = $this->products_model->getBrandsName($brands);
            $data['brandList'] = $brands;
        }
        // if(!empty($min_price) && !empty($max_price)){

        //     $data['price'] = $min_price.' to '.$max_price;

        // }

        // echo "<pre>"; print_r($brands);


        echo json_encode(array(
            'view_html' => $this->load->view('frontend/products/products_filter_top', $data, true),
            

        ));

    }

    public function products_ajax()
    {
        //   echo "<pre>"; print_r($_POST); die;

        $url = explode('/', $this->input->post('url'));
        if ($url[1] == 'products') {
            $paginationUrl = base_url('products/products_filter');
        } else {
            $paginationUrl = base_url('/') . $url[1] . '/' . @$url[2];
        }
        $per_page = 12;

        $result = $this->products_model->getProductsAjax($this->input->post());
        //echo "<pre>"; print_r($result); die;
        $count = $this->products_model->getProductsAjax($this->input->post(), 0);

        $maxPrice = 10000;
        $max = $this->products_model->getProductsAjax($this->input->post(), 2);

        if (!empty($max) && isset($max->selling_price)) {
            $maxPrice = $max->selling_price;
        }

        $config = array();
        $config['base_url'] = $paginationUrl;
        $config['total_rows'] = $count - 12;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = TRUE;
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

        $data['result'] = $result;
        $data['compare'] = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        $data['pagination'] = $this->pagination->create_links();

        $data['maxPrice'] = $maxPrice;
        $count = count($data['result']);
        if ($count <= 0) {
            $data['products'] = $this->front_model->get_top_products($limit = 50, []);
        }
        echo json_encode(array(
            'view_html' => $this->load->view('frontend/products/ajax_call', $data, true),
            'maxPrice' => $maxPrice

        ));

    }


    public function products_sidebar()
    {
        // echo "<pre>"; print_r($this->input->post()); die;
        $result = $this->products_model->getBrands($this->input->post());
        echo json_encode(array('result' => $result));
    }


    public function product_compare_data()
    {
        $action = $this->input->post('action');
        $pid = $this->input->post('pid');
        $compare = $this->session->userdata('compare_products');
        if ($action == 'add') {
            if (!empty($compare)) {
                if (count($compare) < 4) {
                    $compare[] = $pid;
                    $this->session->set_userdata('compare_products', $compare);
                } elseif (count($compare) == 4) {
                    return false;
                }
            } else {
                $this->session->set_userdata('compare_products', [$pid]);
            }
        } elseif ($action == 'remove') {
            if (($key = array_search($pid, $compare)) !== false) {
                unset($compare[$key]);
            }
            $this->session->set_userdata('compare_products', $compare);
        }

        $count = $this->session->userdata('compare_products') ? $this->session->userdata('compare_products') : [];
        echo count($count);
    }


    public function product_compare()
    {
        $data = $this->data;
        $data['title'] = 'Product Compare';
        $compare = $this->session->userdata('compare_products');
        $this->session->set_userdata('compare_products', []);
        if (!empty($compare) && count($compare) < 2) {
            return [
                'status' => false,
                'message' => "Please select at-least 2 products"
            ];
        }

        $data['products'] = $this->products_model->getCompareProducts($compare);
        $this->load->view('frontend/products/products_compare', $data);


    }

    public function getProductByBrand()
    {
        $brandid = $this->input->post('id');
        $data['product'] = $this->products_model->getProductByBrand($brandid);
        echo json_encode($data);


    }
}
