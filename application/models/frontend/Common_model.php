<?php

class Common_model extends CI_Model
{

    public function get_data($table, $where)
    {
        $this->db->where($where);
        return $this->db->get($table)->row();
    }

    public function get_data_array($table, $where)
    {
        $this->db->where($where);
        return $this->db->get($table)->result_array();
    }

    public function get_single_data($table, $where = array())
    {
        if (!empty($where))
            $this->db->where($where);
        $q = $this->db->get($table)->row_array();
        // echo "<pre>"; print_r($q); die;
        // echo $this->db->last_query(); die;
        return $q;
    }

    public function get_product($slug)
    {
        $this->db->select("ci_products.*, ci_merchant.specific_promotion");
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', '
                Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', '
                Left');
        $this->db->where('ci_products.slug', $slug);
        $this->db->where('merchant_products.selling_price >', 0.01);

        $this->db->limit(1);
        return $this->db->get()->row_array();
    }

    public function getCMSPages()
    {
        $this->db->select("title, meta_title, slug, status");
        $this->db->from('cms_management');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_data_like($table, $where = array(), $column = '*', $like, $limit = 5)
    {
        $this->db->select($column);
        $this->db->from($table);
        foreach ($like as $key => $value) {
            $this->db->like("$key", $value);
        }
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($limit);
        return $this->db->get()->result_array();

    }

    public function get_data_search_($table, $where = array(), $like, $limit = 5)
    {
        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,merchant_products.selling_price, merchant_products.cost_price,ci_products.image,ci_category.slug as category_slug");
        $this->db->select('(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', '
                Left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', '
                Left');
        foreach ($like as $key => $value) {
            $this->db->like("$key", $value);
        }
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($limit);
        $this->db->order_by('ci_products.id', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_data_search($table, $where = array(), $like, $limit = 5)
    {
        $this->load->driver('cache', array('adapter' => 'memcached'));
        $cache_key = implode('_', $where).'_'.implode(':', $like).$limit;
        $cached = $this->cache->get('search.'.$cache_key);
        if (!empty($cached)) {
            return $cached;
        }
        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,merchant_products.selling_price, merchant_products.cost_price,ci_products.image,ci_category.slug as category_slug");
        $this->db->select('(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) AS percent_discount', FALSE);

        $this->db->from('ci_products');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        // Commented out because this table is not used.
        //$this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');

        foreach ($like as $key => $value) {
            $this->db->like("$key", $value);
        }

        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->where("merchant_products.stock", 1);

        $this->db->limit($limit);

        $this->db->order_by('merchant_products.selling_price', 'asc');
        $this->db->group_by('ci_products.id');
        $q = $this->db->get()->result_array();
        $this->cache->save('search.'.$cache_key, $q, 300);
        return $q;

    }

    public function get_searched_data($table, $where = array(), $like, $limit = 5)
    {

        $this->db->select("*");
        $this->db->from($table);
        foreach ($like as $key => $value) {
            $this->db->like($key, $value);
        }
        if (!empty($where))
            $this->db->where($where);
        $this->db->limit($limit);
        return $this->db->get()->result_array();

    }

    public function get_brand_searched_data($filterVal)
    {
        $this->db->select('*');
        $this->db->from('ci_brand');
        $this->db->like('ci_brand.alias', $filterVal['alias']);
        $this->db->limit(3);
        $this->db->distinct('slug');

        $q = $this->db->get()->result_array();

        $brandList = [];
        $slugList = [];

        foreach ($q as $key => $value) {
            if (in_array($value->slug, $slugList)) {
                continue;
            }

            array_push($slugList, $value->slug);
            $brandList[] = $value;
        }

        foreach ($brandList as $key => $value) {
            $this->db->select('ci_products.id');
            $this->db->from('ci_products');
            /*new join add*/
            $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'inner');
            /*new join add*/
            $this->db->where('ci_products.brand_id', $value['id']);
            /*new where condition add*/
            $this->db->where('merchant_products.selling_price >', 0.01);
            /*new where condition add*/
            $p = $this->db->count_all_results();
            $brandList[$key]['totalProduct'] = $p;
        }

        return $brandList;
    }

    public function get_searched_subcategory($table, $where = array(), $like, $limit = 5)
    {
        $this->db->select("ci_subcategory.id,ci_subcategory.name, ci_subcategory.slug, ci_category.slug AS category_slug ");
        $this->db->from($table);
        $this->db->join('ci_category', 'ci_category.id = ci_subcategory.category_id', 'Left');
        foreach ($like as $key => $value) {
            $this->db->like("ci_subcategory.name", $value);
        }
        if (!empty($where))
            $this->db->where(array('ci_subcategory.status' => 1));
        $this->db->limit($limit);
        $p = $this->db->get()->result_array();
        foreach ($p as $key => $value) {
            $this->db->select('ci_products.id');
            $this->db->from('ci_products');
            /*new join add*/
            $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'inner');
            /*new join add*/
            $this->db->where('ci_products.subCategory_id', $value['id']);
            /*new where condition add*/
            $this->db->where('merchant_products.selling_price >', 0.01);
            $this->db->where('merchant_products.stock >', 0);
            /*new where condition add*/
            $r = $this->db->count_all_results();
            $p[$key]['totalProduct'] = $r;
        }
        /*echo "<pre>";
        print_r($p);die;*/
        return $p;
    }

    public function get_searched_category($table, $where = array(), $like, $limit = 5)
    {
        $this->db->select("ci_category.name, ci_category.slug, ci_category.slug AS category_slug ");
        $this->db->from($table);
        //$this->db->join('ci_category','ci_category.id = ci_subcategory.category_id','Left');
        foreach ($like as $key => $value) {
            $this->db->like("ci_category.name", $value);
        }
        if (!empty($where))
            $this->db->where(array('ci_category.status' => 1));
        $this->db->limit($limit);
        return $this->db->get()->result_array();
    }

    public function add_data($table, $data)
    {
        $this->db->insert($table, $data);
        return true;
    }

    public function checkSlugExsits($slug_name, $slug_name2)
    {
        $categories_list = array(
            'Computers',
            'Electronics',
            'Garden-Outdoor',
            'Health-Beauty',
            'Baby-Kids',
            'Fashion',
            'Phones',
            'Sports-Fitness',
            'Books-Music-Film',
            'Special-Offers',
            'Homeware-Furniture',
            'Events',
            'books',
            'Home-Appliances',
            'food-drink',
            'Voucher-Codes-Sales-Events',
            'top-level-category'
        );

        if (in_array($slug_name, $categories_list, true)) {
            $this->db->select('1');
            $this->db->from('ci_products');
            $this->db->where('slug', $slug_name2);
            if ($this->db->count_all_results() > 0) {
                return 1;
            }
            return 3;
        }

        $this->db->select('1');
        $this->db->from('ci_products');
        $this->db->where('slug', $slug_name2);
        if ($this->db->count_all_results() > 0) {
            return 1;
        }

        $this->db->select('1');
        $this->db->from('ci_brand');
        $this->db->where('slug', $slug_name);
        $this->db->where('status', 1);
        if ($this->db->count_all_results() > 0) {
            return 2;
        }
        $this->db->select('1');
        $this->db->from('ci_category');
        $this->db->where('slug', $slug_name);
        if ($this->db->count_all_results() > 0) {
            return 3;
        }


        return 0;
    }


    public function checkSlugExsits1($slug_name)
    {
        $this->db->where('slug', $slug_name);
        $this->db->from('ci_products');
        $q = $this->db->get()->num_rows();
        if ($q == 0) {

            $this->db->where('slug', $slug_name);
            $this->db->where('status', 1);
            $this->db->from('ci_brand');
            $q1 = $this->db->get()->num_rows();
            // echo $q; die;
            if ($q1 == 0) {

                $this->db->where('slug', $slug_name);
                $this->db->from('ci_category');
                $q1 = $this->db->get()->num_rows();
                return 3;


            } else {

                return 2;
            }

        } else {

            return $q;
        }
    }

    public function update_product_visit_count($product_id, $category_id)
    {


        $this->db->where(['product_id' => $product_id]);
        $already = $this->db->get('product_visit')->result();
        if (!empty($already)) {
            $this->db->where(['product_id' => $product_id]);
            $this->db->set('visit_count', 'visit_count + 1', FALSE);
            $this->db->update('product_visit');
            return;
        }
        $data = array(
            'product_id' => $product_id,
            'visit_count' => 1,
            'category_id' => $category_id
        );
        $this->db->insert('product_visit', $data);
    }
}

?>
