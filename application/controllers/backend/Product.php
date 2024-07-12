<?php defined('BASEPATH') or exit('No direct script access allowed');

class Product extends CI_Controller
{

    public function __construct()
    {

        parent::__construct();
        if (!$this->session->has_userdata('is_admin_login')) {
            redirect('backend/auth/login');
        }
        $this->load->library('pagination');

        $this->load->model('backend/common_model', 'common_model');
        $this->load->model('backend/category_model', 'category_model');
    }

    //-----------------------------------------------------------
    public function index()
    {


        /*$data = $conditions = array();
        $conditions['returnType'] = 'count';
        $totalRec = $this->common_model->getRows($conditions);

        $config['base_url']    = base_url().'backend/product/index/';
        $config['per_page'] = 15;
        $config['total_rows'] = $totalRec;
        $config['full_tag_open'] = '<ul class="ml-auto">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt; Prev';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';     */

        //$this->pagination->initialize($config);
        //$offset=$this->uri->segment(4);

        //$data['product_list']=$this->common_model->get_product_data($config['per_page'] , $offset);
        //$data['totalRec'] = $totalRec;

        $data['title'] = 'Product';
        $data['categories'] = $this->category_model->getCategort();
        //ini_set('memory_limit', '-1');

        $this->load->view('backend/product/index', $data);
    }

    public function added_products()
    {
        $data['title'] = 'Added Products';
        $data['api'] = 'backend/product/viewMyProducts';
        //ini_set('memory_limit', '-1');

        $this->load->view('backend/product/added', $data);
    }

    public function viewProducts()
    {
        $categoryname = $this->input->get('sSearch_1');
        $response = array(
            "sEcho" => intval($this->input->get('sEcho')),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );
        $columnField = array("ci_products.id", "ci_products.name", "ci_products.image", "merchant_products.selling_price", "", "", "ci_products.top_deal", "ci_products.m_top", "", "", "discount_percent");
        $start = $this->input->get('iDisplayStart');
        $recordPerPage = $this->input->get('iDisplayLength');

        if ($start == "") {
            $start = 0;
        }

        if ($recordPerPage == "") {
            $recordPerPage = 10;
        } else if ($recordPerPage == "-1") {
            $recordPerPage = 99999999999;
        }

        $orderBy = "";
        $orderByCol = $this->input->get('iSortCol_0');
        $orderByType = $this->input->get('sSortDir_0');

        if ($orderByType === "") {
            $orderByType = "desc";
        }

        if ($orderByCol != "") {
            if (isset($columnField[$orderByCol]) && $columnField[$orderByCol] != "") {
                $orderBy = $columnField[$orderByCol] . " " . $orderByType;
            } else {
                $orderBy = "ci_products.name " . $orderByType;
            }
        } else {
            $orderBy = "ci_products.name " . $orderByType;
        }

        $searchTerm = $this->input->get('sSearch');
        $whereCondition = "";
        if ($categoryname != "") {
            $whereCondition = "ci_products.category_id=$categoryname";
        }
        if ($searchTerm != "") {
            $searchTerm = addslashes($searchTerm);
            if ($categoryname != "") $whereCondition .= ' AND ';
            $whereCondition .= '(';
            for ($i = 0; $i < count($columnField); $i++) {
                if ($columnField[$i] != "") {
                    $whereCondition .= $columnField[$i] . " LIKE '%" . ($searchTerm) . "%' OR ";
                }

            }
            $whereCondition = substr_replace($whereCondition, "", -3);
            $whereCondition .= ')';
        }

        $pageRowData = $this->common_model->getProductListPage($start, $recordPerPage, $orderBy, $whereCondition, false);
        $countRecord = $this->common_model->getProductListPage($start, $recordPerPage, $orderBy, $whereCondition, true);

        $response = array(
            "sEcho" => intval($this->input->get('sEcho')),
            "iTotalRecords" => $countRecord,
            "iTotalDisplayRecords" => $countRecord,
            "aaData" => array()
        );

        $recordListArray = [];
        $counter = $start;

        foreach ($pageRowData as $row) {
            if ($row->m_top != "0") {
                $checked = "checked";
            } else {
                $checked = "";
            }

            if ($row->top_deal != "0") {
                $top_deal = "checked";
            } else {
                $top_deal = "";
            }

            if ($row->stock == "1") {
                $stock = "checked";
            } else {
                $stock = "";
            }

            $recordListArray[] = array(
                ++$counter,
                $row->id,
                $row->name,
                "<img alt='product image' width='100px' height='150px' style='object-fit: cover' src='" . $row->image . "'>",
                '&euro;' . $row->product_price,
                date('Y-m-d', strtotime($row->merchant_updated_at)),
                "<input class='tgl_top_deal tgl-ios' data-id='" . $row->id . "' id='cb1_" . $row->id . "' type='checkbox' value='" . $top_deal . "' " . $top_deal . "><label for='cb1_" . $row->id . "'></label>",
                "<input class='tgl_manual_top tgl-ios' data-id='" . $row->id . "' id='cb_" . $row->id . "' type='checkbox' value='" . $checked . "' " . $checked . "><label for='cb_" . $row->id . "'></label>",
                $row->top_status,
                "<input class='tgl_stock tgl-ios' data-id='" . $row->id . "' id='cb2_" . $row->id . "' type='checkbox' value='" . $stock . "' " . $stock . "><label for='cb2_" . $row->id . "'></label>",
                number_format(100 - $row->selling_price/$row->cost_price * 100, 2)." %"
            );

        }
        $response["aaData"] = $recordListArray;

        echo json_encode($response);
    }

    public function viewMyProducts()
    {
        $response = array(
            "sEcho" => intval($this->input->get('sEcho')),
            "iTotalRecords" => 0,
            "iTotalDisplayRecords" => 0,
            "aaData" => array()
        );
        $columnField = array("ci_products.id", "ci_products.name", "ci_products.image", "merchant_products.selling_price");
        $start = $this->input->get('iDisplayStart');
        $recordPerPage = $this->input->get('iDisplayLength');

        if ($start == "") {
            $start = 0;
        }

        if ($recordPerPage == "") {
            $recordPerPage = 10;
        } else if ($recordPerPage == "-1") {
            $recordPerPage = 99999999999;
        }

        $orderBy = "";
        $orderByCol = $this->input->get('iSortCol_0');
        $orderByType = $this->input->get('sSortDir_0');

        if ($orderByType !== "") {
            $orderByType = "desc";
        }

        if ($orderByCol != "") {
            if (isset($columnField[$orderByCol]) && $columnField[$orderByCol] != "") {
                $orderBy = "ci_products.id " . $orderByType;
            } else {
                $orderBy = "ci_products.id " . $orderByType;
            }
        } else {
            $orderBy = "ci_products.id " . $orderByType;
        }

        $id = $this->category_model->getMerchantCategoryId();
        $whereCondition = "category_id=$id";

        $searchTerm = $this->input->get('sSearch');
        if ($searchTerm != "") {
            $searchTerm = addslashes($searchTerm);
            $whereCondition .= 'AND (';
            for ($i = 0; $i < count($columnField); $i++) {
                if ($columnField[$i] != "") {
                    $whereCondition .= $columnField[$i] . " LIKE '%" . ($searchTerm) . "%' OR ";
                }

            }
            $whereCondition = substr_replace($whereCondition, "", -3);
            $whereCondition .= ')';
        }

        $pageRowData = $this->common_model->getProductListPage($start, $recordPerPage, $orderBy, $whereCondition, false);
        $countRecord = $this->common_model->getProductListPage($start, $recordPerPage, $orderBy, $whereCondition, true);

        $response = array(
            "sEcho" => intval($this->input->get('sEcho')),
            "iTotalRecords" => $countRecord,
            "iTotalDisplayRecords" => $countRecord,
            "aaData" => array()
        );

        $recordListArray = [];
        $counter = $start;

        foreach ($pageRowData as $row) {
            if ($row->m_top == "1") {
                $checked = "checked";
            } else {
                $checked = "";
            }

            if ($row->top_deal == "1") {
                $top_deal = "checked";
            } else {
                $top_deal = "";
            }
            $id = $row->name_wp;
            $recordListArray[] = array(
                ++$counter,
                $row->id,
                $row->name,
                "<img alt='product image' width='100px' height='150px' style='object-fit: cover' src='" . $row->image . "'>",
                '&euro;' . $row->product_price,
                date('Y-m-d', strtotime($row->merchant_updated_at)),
                "<input class='tgl_top_deal tgl-ios' data-id='" . $row->id . "' id='cb1_" . $row->id . "' type='checkbox' value='" . $top_deal . "' " . $top_deal . "><label for='cb1_" . $row->id . "'></label>",
                "<input class='tgl_manual_top tgl-ios' data-id='" . $row->id . "' id='cb_" . $row->id . "' type='checkbox' value='" . $checked . "' " . $checked . "><label for='cb_" . $row->id . "'></label>",
                $row->top_status,
                "<a href='/backend/product/delete/" . $id . "' class='btn btn-danger btn-sm'>Remove</a> <a class='btn btn-success btn-sm mt-3' href='/backend/product/edit/" . $id . "'>Update</a>"
            );

        }
        $response["aaData"] = $recordListArray;

        echo json_encode($response);
    }


    public function product_datatable()
    {
        // echo  "hi"; die;
        ini_set('memory_limit', '-1');
        $products = $this->common_model->get_product_data(null, 0);
        $data = array();
        $i = 0;
        foreach ($products as $row) {
            $data[] = array(
                ++$i,
                $row['id'],
                $row['name'],
                '<img alt="' . $row['name'] . '" height="150px" width="100px" src="' . $row['image'] . '">',
                $row['currency'] . $row['selling_price'],
                $row['updated_at'],
                json_encode([$row['m_top'] == 1 ? 'checked' : '', $row['id']]),
                $row['m_top'] == 1 ? 'Auto Top' : '',
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
            'm_top' => $this->input->post('m_top')
        );
        echo $this->common_model->update_data('ci_products', $where, $data);
    }

    public function change_top_deal()
    {
        $where = array();

        $where['id'] = $this->input->post('id');
        $data = array(
            'top_deal' => $this->input->post('top_deal')
        );
        echo $this->common_model->update_data('ci_products', $where, $data);
    }

    public function change_stock()
    {
        $id = $this->input->post('id');
        $stock = $this->input->post('stock');
        echo $this->common_model->update_stock($id, $stock);
    }

    public function add()
    {
        $data['title'] = 'New Product';
        $data['merchants'] = $this->common_model->getMerchantList();
        $id = $this->category_model->getMerchantCategoryId();
        $data['subCategories'] = $this->category_model->getSubCategort($id);
        $data['brands'] = $this->common_model->getBrandData();
        $this->load->view('backend/product/add', $data);
    }

    public function create()
    {
        $imageUrl = "";
        $config['upload_path'] = './uploads/product';  // Specify the upload directory
        $config['allowed_types'] = 'gif|jpg|jpeg|png';  // Specify allowed file types
        $config['max_size'] = 2048;  // Set the maximum file size in kilobytes
        $this->load->library('upload', $config);  // Load the upload library
        if (!$this->upload->do_upload('image')) {
            $error = $this->upload->display_errors();
            echo $error;
        } else {
            $data = $this->upload->data();
            $imageUrl = 'https://onsalenow.ie/uploads/product/'.$data['file_name'];
        }
        $category_id = $this->category_model->getMerchantCategoryId();
        $merchant_id = $this->input->post('merchant_id');
        $subcategory_id = $this->input->post('subcategory_id');
        $brand_id = $this->input->post('brand_id');
        $product_name = $this->input->post('product_name');
        $slug = $this->input->post('slug');
        $description = $this->input->post('description');
        $wp_name = $this->input->post('wp_name');
        $store_url = $this->input->post('store_url');
        $selling_price = $this->input->post('selling_price');
        $cost_price = $this->input->post('cost_price');
        $sale_start_date = $this->input->post('sale_start_date');
        $sale_end_date = $this->input->post('sale_end_date');

        $merchant_product = [
            'merchant_id' => $merchant_id,
            'name_wp' => $wp_name,
            'selling_price' => $selling_price,
            'cost_price' => $cost_price,
            'sale_start_date' => $sale_start_date,
            'sale_end_date' => $sale_end_date,
            'merchant_store_url' => $store_url
        ];
        $ci_product = [
            'category_id' => $category_id,
            'subCategory_id' => $subcategory_id,
            'brand_id' => $brand_id,
            'name_wp' => $wp_name,
            'slug' => $slug,
            'description' => $description,
            'name' => $product_name,
            'image' => $imageUrl
        ];

        $this->common_model->addNewProduct($merchant_product, $ci_product);
        redirect('backend/Product/index');
    }

    public function delete()
    {
        $name_wp = $this->uri->segment(4);
        $this->common_model->delete_row('ci_products', "name_wp='$name_wp'");
        $this->common_model->delete_row('merchant_products', "name_wp='$name_wp'");
        redirect('backend/Product/added_products');
    }

    public function edit()
    {
        $name_wp = $this->uri->segment(4);

        $data['title'] = 'Edit Product';
        $data['merchants'] = $this->common_model->getMerchantList();
        $id = $this->category_model->getMerchantCategoryId();
        $data['subCategories'] = $this->category_model->getSubCategort($id);
        $data['brands'] = $this->common_model->getBrandData();

        $products = $this->common_model->getProductByWPName($name_wp);
        $data['product'] = $products[0];

        $this->load->view('backend/product/edit', $data);
    }

    public function update()
    {
        $name_wp = $this->uri->segment(4);

        $imageUrl = "";
        if (isset($_FILES['image'])) {
            $config['upload_path'] = './uploads/product';  // Specify the upload directory
            $config['allowed_types'] = 'gif|jpg|jpeg|png';  // Specify allowed file types
            $config['max_size'] = 2048;  // Set the maximum file size in kilobytes
            $this->load->library('upload', $config);  // Load the upload library
            if (!$this->upload->do_upload('image')) {
                $error = $this->upload->display_errors();
                echo $error;
            } else {
                $data = $this->upload->data();
                $imageUrl = 'https://onsalenow.ie/uploads/product/'.$data['file_name'];
            }
        }
        $category_id = $this->category_model->getMerchantCategoryId();
        $merchant_id = $this->input->post('merchant_id');
        $subcategory_id = $this->input->post('subcategory_id');
        $brand_id = $this->input->post('brand_id');
        $product_name = $this->input->post('product_name');
        $slug = $this->input->post('slug');
        $description = $this->input->post('description');
        $wp_name = $this->input->post('wp_name');
        $store_url = $this->input->post('store_url');
        $selling_price = $this->input->post('selling_price');
        $cost_price = $this->input->post('cost_price');
        $sale_start_date = $this->input->post('sale_start_date');
        $sale_end_date = $this->input->post('sale_end_date');

        $merchant_product = [
            'merchant_id' => $merchant_id,
            'name_wp' => $wp_name,
            'selling_price' => $selling_price,
            'cost_price' => $cost_price,
            'sale_start_date' => $sale_start_date,
            'sale_end_date' => $sale_end_date,
            'merchant_store_url' => $store_url
        ];
        $ci_product = [
            'category_id' => $category_id,
            'subCategory_id' => $subcategory_id,
            'brand_id' => $brand_id,
            'name_wp' => $wp_name,
            'slug' => $slug,
            'description' => $description,
            'name' => $product_name,
        ];
        if ($imageUrl != "")
            $ci_product['image'] = $imageUrl;

        $this->common_model->updateProduct($merchant_product, $ci_product, $wp_name);
        redirect($this->agent->referrer());
    }
}
