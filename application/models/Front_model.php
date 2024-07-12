<?php

class Front_model extends CI_Model
{

    public function menu_data()
    {
        $this->db->where('status', 1);
        $this->db->limit(5);
        return $this->db->get('ci_menu')->result();
    }

    public function web_logo()
    {
        $this->db->select('*');
        $this->db->where('id', 1);
        $query = $this->db->get('ci_general_settings');
        return $query->row();
    }

    public function getBrandId($slug)
    {

        $this->db->where('slug', $slug);
        $this->db->from('ci_brand');
        $q = $this->db->get()->row();
        return $q;
    }

    public function get_data($table, $where = array(), $orderBy = 'ASC', $limit = null)
    {
        //echo "<pre>"; print_r($where); die;
        // echo $table; die;
        $this->db->select("*");
        if (!empty($where)) {
            $this->db->where($where);
        }

        if ($table == 'ci_category' || $table == 'ci_subcategory') {
            $this->db->order_by('name', $orderBy);
        } else {
            $this->db->order_by('id', $orderBy);
        }
        if ($limit != null) {
            $this->db->limit($limit);
        }

        $result = $this->db->get($table)->result();
        // echo "<pre>"; print_r($result); die;
        // echo $this->db->last_query(); die;
        return $result;
    }

    public function get_data_list($table, $where, $orderBy = 'ASC', $limit = null)
    {
        $this->db->select('*');
        $this->db->from('ci_subcategory');
        $this->db->where('category_id', $where);
        $this->db->order_by('name', $orderBy);
        $result = $this->db->get()->result();
        return $result;
    }

    public function getTopCategoryList()
    {
        $this->db->select("*");
        $this->db->from('ci_category');
        $this->db->where('status', '1');
        $this->db->order_by('is_top', 'DESC');

        $this->db->limit(6);
        $this->db->group_by('name');
        return $this->db->get()->result();
    }

    public function getAllCategoryList()
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_category.id, ci_category.name, ci_category.slug, ci_category.image,
                MIN(
                    CASE
                    WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                    WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                    ELSE ROUND(merchant_products.selling_price, 2)
                    END
                ) AS selling_price,
                MIN(
                    CASE
                    WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[0] . ", 2)
                    WHEN merchant_products.currency='$' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[1] . ", 2)
                    ELSE ROUND(merchant_products.cost_price, 2)
                    END
                ) AS cost_price");
        $this->db->select('cost_price - selling_price AS discount', false);
        $this->db->from('ci_category');
        $this->db->join('ci_products', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->where('ci_category.status', '1');
        $this->db->where('ci_products.status', '1');
        $this->db->where('selling_price >', 0.01);
        $this->db->group_by('ci_category.name');
        $this->db->order_by('ci_category.name', 'ASC');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_branddata()
    {
        $this->db->select("*");
        $this->db->from('ci_brand');
        $this->db->where('show_home', '1');
        $this->db->order_by('brand_name', 'RANDOM');
        $this->db->group_by('slug');
        $this->db->limit(6);

        $result = $this->db->get()->result();

        return $result;
    }

    public function get_top_products($limit = 6, $like = array())
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_products.id, ci_products.slug, ci_products.name, ci_products.image, ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,
            CASE
                WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.selling_price, 2)
            END AS selling_price,
            CASE
                WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.cost_price, 2)
            END AS cost_price,
            ci_category.id as category_id, ci_category.name as category_name, ci_category.slug as category_slug");
        foreach ($like as $key => $value) {
            $this->db->like("$key", $value);
        }
        $this->db->from('ci_products');

        $this->db->where('ci_products.m_top', '1');
        $this->db->or_where('ci_products.a_top >', 0);

        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->order_by('ci_products.a_top', 'ASC');

        $this->db->limit($limit);

        $result = $this->db->get()->result();
        return $result;
    }

    public function get_bigsale_products($limit = 16, $like = array(), $discounts, $In_Stock = null, $sort_by = null, $filter = array(), $totalCount, $priceRange)
    {
        $exchangeRate = get_exchange_rate();
        $minPercent = (100 - $discounts[1]) / 100;
        $maxPercent = (100 - $discounts[0]) / 100;
    
        $this->db->start_cache();
    
        $this->db->select("
            ci_products.id, ci_products.slug, ci_products.name, ci_products.image, 
            ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.image as brandimage,
            merchant_products.selling_price, merchant_products.cost_price,
            ci_category.id as category_id, ci_category.name as category_name, ci_category.slug as category_slug,
            ci_subcategory.id as subcategory_id, ci_subcategory.name as subcategory_name
        ");
    
        foreach ($like as $key => $value) {
            $this->db->like($key, $value);
        }
    
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
    
        if (!empty($filter['brands'])) {
            $this->db->where_in('ci_products.brand_id', $filter['brands']);
        }
        if (!empty($filter['categories'])) {
            $this->db->where_in('ci_products.subCategory_id', $filter['categories']);
        }
    
        $this->db->where('merchant_products.cost_price <>', 0);
        $this->db->where('merchant_products.selling_price <>', 0);
        $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
        $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
    
        if ($priceRange[0] !== null && $priceRange[1] !== null) {
            $this->db->where('merchant_products.selling_price >=', $priceRange[0]);
            $this->db->where('merchant_products.selling_price <=', $priceRange[1]);
        }
    
        if ($In_Stock) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }
    
        if ($sort_by === "asc") {
            $this->db->order_by('merchant_products.selling_price', 'ASC');
        } else if ($sort_by === "desc") {
            $this->db->order_by('merchant_products.selling_price', 'DESC');
        } else {
            $this->db->order_by('ci_products.a_top', 'ASC');
        }
    
        $this->db->stop_cache();
    
        if ($this->uri->total_segments() >= 3) {
            $offset = $this->uri->segment(3);
            if ($totalCount < $limit * $offset) {
                $offset = ceil($totalCount / $limit);
            }
            if ($offset < 1) $offset = 1;
            $this->db->limit($limit, ($offset - 1) * $limit);
        } else {
            $this->db->limit($limit);
        }
    
        $products = $this->db->get()->result();
    
        $this->db->flush_cache();
    
        $this->db->start_cache();
    
        $this->db->select("
            ci_brand.id as brand_id, ci_brand.brand_name, COUNT(DISTINCT ci_products.id) as product_count,
            ci_category.id as category_id, ci_category.name as category_name, COUNT(DISTINCT ci_products.id) as category_product_count,
            ci_subcategory.id as subcategory_id, ci_subcategory.name as subcategory_name, COUNT(DISTINCT ci_products.id) as subcategory_product_count
        ");
        foreach ($like as $key => $value) {
            $this->db->like($key, $value);
        }
    
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->where('merchant_products.cost_price <>', 0);
        $this->db->where('merchant_products.selling_price <>', 0);
        $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
        $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
        if ($priceRange[0] !== null && $priceRange[1] !== null) {
            $this->db->where('merchant_products.selling_price >=', $priceRange[0]);
            $this->db->where('merchant_products.selling_price <=', $priceRange[1]);
        }
        if (!empty($filter['categories'])) {
            $this->db->where_in('ci_products.subCategory_id', $filter['categories']);
        }
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'left');
        $this->db->group_by(['ci_brand.id', 'ci_category.id', 'ci_subcategory.id']);
        $this->db->order_by('ci_brand.brand_name, ci_category.name, ci_subcategory.name');
    
        $result_counts = $this->db->get()->result();
        $this->db->flush_cache();
    
        $brands = [];
        $categories = [];
        $subCategories = [];
    
        foreach ($result_counts as $row) {
            if (!isset($brands[$row->brand_id])) {
                $brands[$row->brand_id] = (object)[
                    'id' => $row->brand_id,
                    'brand_name' => $row->brand_name,
                    'product_count' => $row->product_count,
                ];
            }
            if (!isset($categories[$row->category_id])) {
                $categories[$row->category_id] = (object)[
                    'id' => $row->category_id,
                    'name' => $row->category_name,
                    'product_count' => $row->category_product_count,
                ];
            }
            if (!isset($subCategories[$row->subcategory_id])) {
                $subCategories[$row->subcategory_id] = (object)[
                    'id' => $row->subcategory_id,
                    'name' => $row->subcategory_name,
                    'product_count' => $row->subcategory_product_count,
                ];
            }
        }
    
        return [$products, array_values($brands), array_values($categories), array_values($subCategories)];
    }
    
    
    // public function get_bigsale_products($limit = 16, $like = array(), $discounts, $In_Stock = null, $sort_by = null, $filter = array(), $totalCount, $priceRange)
    // {
    //     // Tính phần trăm giảm giá
    //     $minPercent = (100 - $discounts[1]) / 100;
    //     $maxPercent = (100 - $discounts[0]) / 100;
    
    //     // Tạo cache key dựa trên các tham số truy vấn
    //     $cacheKey = md5(serialize([$limit, $like, $discounts, $In_Stock, $sort_by, $filter, $totalCount, $priceRange, $this->uri->segment(3)]));
    //     $cachedData = $this->cache->get($cacheKey);
    
    //     if ($cachedData !== FALSE) {
    //         return $cachedData;
    //     }
    
    //     $this->db->start_cache();
    
    //     // Chọn các cột cần thiết
    //     $this->db->select("
    //         ci_products.id, ci_products.slug, ci_products.name, ci_products.image, 
    //         ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.image as brandimage,
    //         merchant_products.selling_price, merchant_products.cost_price,
    //         ci_category.id as category_id, ci_category.name as category_name, ci_category.slug as category_slug,
    //         ci_subcategory.id as subcategory_id, ci_subcategory.name as subcategory_name
    //     ");
    
    //     // Áp dụng các bộ lọc "like"
    //     foreach ($like as $key => $value) {
    //         $this->db->like($key, $value);
    //     }
    
    //     // Kết hợp các bảng cần thiết
    //     $this->db->from('ci_products');
    //     $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'left');
    //     $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'left');
    //     $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'left');
    //     $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
    
    //     // Áp dụng các bộ lọc thương hiệu và danh mục
    //     if (!empty($filter['brands'])) {
    //         $this->db->where_in('ci_products.brand_id', $filter['brands']);
    //     }
    //     if (!empty($filter['categories'])) {
    //         $this->db->where_in('ci_products.subCategory_id', $filter['categories']);
    //     }
    
    //     // Áp dụng các bộ lọc giá và phần trăm giảm giá
    //     $this->db->where('merchant_products.cost_price <>', 0);
    //     $this->db->where('merchant_products.selling_price <>', 0);
    //     $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
    //     $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
    
    //     if ($priceRange[0] !== null && $priceRange[1] !== null) {
    //         $this->db->where('merchant_products.selling_price >=', $priceRange[0]);
    //         $this->db->where('merchant_products.selling_price <=', $priceRange[1]);
    //     }
    
    //     // Áp dụng bộ lọc tồn kho
    //     if ($In_Stock) {
    //         $this->db->where('merchant_products.stock', $In_Stock);
    //     }
    
    //     // Áp dụng sắp xếp
    //     if ($sort_by === "asc") {
    //         $this->db->order_by('merchant_products.selling_price', 'ASC');
    //     } else if ($sort_by === "desc") {
    //         $this->db->order_by('merchant_products.selling_price', 'DESC');
    //     } else {
    //         $this->db->order_by('ci_products.a_top', 'ASC');
    //     }
    
    //     $this->db->stop_cache();
    
    //     // Tính toán offset và giới hạn kết quả
    //     if ($this->uri->total_segments() >= 3) {
    //         $offset = $this->uri->segment(3);
    //         if ($totalCount < $limit * $offset) {
    //             $offset = ceil($totalCount / $limit);
    //         }
    //         if ($offset < 1) $offset = 1;
    //         $this->db->limit($limit, ($offset - 1) * $limit);
    //     } else {
    //         $this->db->limit($limit);
    //     }
    
    //     // Thực hiện truy vấn chính
    //     $products = $this->db->get()->result();
    
    //     $this->db->flush_cache();
    
    //     // Tính toán số lượng thương hiệu, danh mục và danh mục con
    //     $this->db->start_cache();
    
    //     $this->db->select("
    //         ci_brand.id as brand_id, ci_brand.brand_name, COUNT(DISTINCT ci_products.id) as product_count,
    //         ci_category.id as category_id, ci_category.name as category_name, COUNT(DISTINCT ci_products.id) as category_product_count,
    //         ci_subcategory.id as subcategory_id, ci_subcategory.name as subcategory_name, COUNT(DISTINCT ci_products.id) as subcategory_product_count
    //     ");
    //     foreach ($like as $key => $value) {
    //         $this->db->like($key, $value);
    //     }
    
    //     $this->db->from('ci_products');
    //     $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
    //     $this->db->where('merchant_products.cost_price <>', 0);
    //     $this->db->where('merchant_products.selling_price <>', 0);
    //     $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
    //     $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
    //     if ($priceRange[0] !== null && $priceRange[1] !== null) {
    //         $this->db->where('merchant_products.selling_price >=', $priceRange[0]);
    //         $this->db->where('merchant_products.selling_price <=', $priceRange[1]);
    //     }
    //     if (!empty($filter['categories'])) {
    //         $this->db->where_in('ci_products.subCategory_id', $filter['categories']);
    //     }
    //     $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'left');
    //     $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'left');
    //     $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'left');
    //     $this->db->group_by(['ci_brand.id', 'ci_category.id', 'ci_subcategory.id']);
    //     $this->db->order_by('ci_brand.brand_name, ci_category.name, ci_subcategory.name');
    
    //     $result_counts = $this->db->get()->result();
    //     $this->db->flush_cache();
    
    //     $brands = [];
    //     $categories = [];
    //     $subCategories = [];
    
    //     foreach ($result_counts as $row) {
    //         if (!isset($brands[$row->brand_id])) {
    //             $brands[$row->brand_id] = (object)[
    //                 'id' => $row->brand_id,
    //                 'brand_name' => $row->brand_name,
    //                 'product_count' => $row->product_count,
    //             ];
    //         }
    //         if (!isset($categories[$row->category_id])) {
    //             $categories[$row->category_id] = (object)[
    //                 'id' => $row->category_id,
    //                 'name' => $row->category_name,
    //                 'product_count' => $row->category_product_count,
    //             ];
    //         }
    //         if (!isset($subCategories[$row->subcategory_id])) {
    //             $subCategories[$row->subcategory_id] = (object)[
    //                 'id' => $row->subcategory_id,
    //                 'name' => $row->subcategory_name,
    //                 'product_count' => $row->subcategory_product_count,
    //             ];
    //         }
    //     }
    
    //     $cachedData = [$products, array_values($brands), array_values($categories), array_values($subCategories)];
    //     if (!$this->cache->save($cacheKey, $cachedData, 300)) {
    //         log_message('error', 'Failed to save cache for get_bigsale_products with key: ' . $cacheKey);
    //     }
    
    //     return $cachedData;
    // }
    



    public function get_bigsale_products_count($like = array(), $discounts, $In_Stock=null, $filter=array(), $priceRange)
    {
        $minPercent = (100 - $discounts[1]) / 100;
        $maxPercent = (100 - $discounts[0]) / 100;
        $this->db->select("
            MAX(
                ROUND(merchant_products.selling_price, 2)
            ) AS max_price,
            MIN(
                ROUND(merchant_products.selling_price, 2)
            ) AS min_price
        ");
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->where('merchant_products.cost_price <> 0');
        $this->db->where('merchant_products.selling_price <> 0');
        $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
        $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
        $totalMinMax = $this->db->get()->row();

        $this->db->select("
            COUNT(DISTINCT ci_products.id) AS total_count,
            MAX(
                ROUND(merchant_products.selling_price / merchant_products.cost_price * 100, 2)
            ) AS max_percent,
            MIN(
                ROUND(merchant_products.selling_price / merchant_products.cost_price * 100, 2)
            ) AS min_percent,
            MAX(
                ROUND(merchant_products.selling_price, 2)
            ) AS max_price,
            MIN(
                ROUND(merchant_products.selling_price, 2)
            ) AS min_price
        ");
        foreach ($like as $key => $value) {
            $this->db->like($key, $value);
        }
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->where('merchant_products.cost_price <> 0');
        $this->db->where('merchant_products.selling_price <> 0');
        $this->db->where("merchant_products.selling_price <= (merchant_products.cost_price * $maxPercent)");
        $this->db->where("merchant_products.selling_price >= (merchant_products.cost_price * $minPercent)");
        if ($priceRange[0] !== null && $priceRange[1] !== null) {
            $this->db->where("merchant_products.selling_price <= ".$priceRange[1]);
            $this->db->where("merchant_products.selling_price >= ".$priceRange[0]);
        }
        if (!empty($filter['brands']) && $filter['brands'][0]) {
            $this->db->where_in('ci_products.brand_id', $filter['brands']);
        }
        if (!empty($filter['categories']) && $filter['categories'][0]) {
            $this->db->where_in('ci_products.subCategory_id', $filter['categories']);
        }
        if ($In_Stock) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        $countResult = $this->db->get()->row();
        return [$countResult->total_count, $countResult->max_percent, $countResult->min_percent, $countResult->max_price, $countResult->min_price, $totalMinMax->max_price, $totalMinMax->min_price];
    }

    public function get_this_week_discounts_products($limit, $where, $In_Stock, $sort_by, $discounts, $priceRange)
    {
        $weeks = 1;
        if ($sort_by == "asc") {
            $sort_by_query = 'selling_price ASC';
        } else if ($sort_by == "desc") {
            $sort_by_query = 'selling_price DESC';
        } else {
            $sort_by_query = 'selling_price / cost_price ASC';
        }
        
        $categoryFilter = "";
        $brandFilter = "";
        $category = $where['categories'];
        $brand = $where['brands'];
        if (!empty($category))
            $categoryFilter = "AND subCategory_id IN ($category)";
        if (!empty($brand))
            $brandFilter = "AND brand_id IN ($brand)";

        $priceRangeQuery = "";
        if ($priceRange[0] !== null && $priceRange[1] !== null) {
            $priceRangeQuery = "AND cost_price - selling_price <= ".$priceRange[1].
            " AND cost_price - selling_price >= ".$priceRange[0];
        }

        $minPercent = (100 - $discounts[1]) / 100;
        $maxPercent = (100 - $discounts[0]) / 100;

        $query = "
            SELECT
                MAX(
                    CEIL(cost_price - selling_price)
                ) AS max_price,
                MIN(
                    FLOOR(cost_price - selling_price)
                ) AS min_price
            FROM price_history_this_week
            WHERE history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND history_date <= CURDATE()
                AND selling_price <= (cost_price * $maxPercent)
                AND selling_price >= (cost_price * $minPercent)
                AND selling_price > 0
                AND cost_price > 0
                AND stock=1
            $brandFilter
            $categoryFilter
        ";

        $result = $this->db->query($query)->result();
        $totalPrice = $this->db->query($query)->row();
        
        $query = "
            SELECT 
                COUNT(DISTINCT price_history_this_week.product_id) AS total_count,
                MAX(
                    ROUND(selling_price / cost_price * 100, 2)
                ) AS max_percent,
                MIN(
                    ROUND(selling_price / cost_price * 100, 2)
                ) AS min_percent,
                MAX(
                    CEIL(cost_price - selling_price)
                ) AS max_price,
                MIN(
                    FLOOR(cost_price - selling_price)
                ) AS min_price
            FROM price_history_this_week
            WHERE history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND history_date <= CURDATE()
                AND selling_price <= (cost_price * $maxPercent)
                AND selling_price >= (cost_price * $minPercent)
                AND selling_price > 0
                AND cost_price > 0
                AND stock=1
            $priceRangeQuery
            $brandFilter
            $categoryFilter
        ";

        $result = $this->db->query($query)->result();
        $totalCount = $this->db->query($query)->row();

        $offset = 0;
        if ($this->uri->total_segments() >= 3) {
            $page = $this->uri->segment(3);
            if ($totalCount->total_count < $limit * $page)
                $page = ceil($totalCount->total_count / $limit);
            if ($page)
                $offset = ($page - 1) * $limit;
        }
        $query = "
        SELECT DISTINCT price_history_this_week.id, price_history_this_week.slug, price_history_this_week.name, price_history_this_week.image, ci_brand.alias as brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.is_image, ci_brand.image AS brandimage,
            ROUND(price_history_this_week.selling_price, 2) as selling_price, price_history_this_week.cost_price,
            ci_category.id AS category_id, ci_category.name AS category_name, ci_category.slug AS category_slug, price_history_this_week.product_id
            FROM price_history_this_week
            LEFT JOIN ci_brand
            ON ci_brand.id = price_history_this_week.brand_id
            LEFT JOIN ci_category
            ON price_history_this_week.category_id = ci_category.id
            WHERE price_history_this_week.history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND price_history_this_week.history_date <= CURDATE()
                AND price_history_this_week.selling_price <= (price_history_this_week.cost_price * $maxPercent)
                AND price_history_this_week.selling_price >= (price_history_this_week.cost_price * $minPercent)
                AND price_history_this_week.selling_price > 0
                AND price_history_this_week.cost_price > 0
                AND price_history_this_week.stock=1
            $priceRangeQuery
            $brandFilter
            $categoryFilter
            ORDER BY $sort_by_query
            LIMIT $limit
            OFFSET $offset;
        ";
        $products = $this->db->query($query)->result();

        $query = "
            SELECT 
                ci_brand.id, ci_brand.alias as brand_name, COUNT(DISTINCT price_history_this_week.product_id) as product_count
            FROM ci_brand
            LEFT JOIN price_history_this_week
                ON ci_brand.id=price_history_this_week.brand_id
            WHERE price_history_this_week.history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND price_history_this_week.history_date <= CURDATE()
                AND price_history_this_week.selling_price <= (price_history_this_week.cost_price * $maxPercent)
                AND price_history_this_week.selling_price >= (price_history_this_week.cost_price * $minPercent)
                AND ci_brand.id IS NOT NULL
                AND price_history_this_week.selling_price > 0
                AND price_history_this_week.cost_price > 0
                AND price_history_this_week.stock=1
            $priceRangeQuery
            $stock_filter
            $categoryFilter
            GROUP BY ci_brand.id
            ORDER BY ci_brand.alias
        ";
        $brands = $this->db->query($query)->result();
        $query = "
            SELECT 
                ci_category.id, ci_category.name, COUNT(DISTINCT price_history_this_week.product_id) as product_count
            FROM ci_category
            LEFT JOIN price_history_this_week
                ON ci_category.id=price_history_this_week.category_id
            WHERE price_history_this_week.history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND price_history_this_week.history_date <= CURDATE()
                AND price_history_this_week.selling_price <= (price_history_this_week.cost_price * $maxPercent)
                AND price_history_this_week.selling_price >= (price_history_this_week.cost_price * $minPercent)
                AND price_history_this_week.selling_price > 0
                AND price_history_this_week.cost_price > 0
                AND price_history_this_week.stock=1
            AND ci_category.id IS NOT NULL
            $priceRangeQuery
            $stock_filter
            GROUP BY ci_category.id
            ORDER BY ci_category.name
        ";
        $categories = $this->db->query($query)->result();

        $query = "
            SELECT 
                DISTINCT ci_subcategory.id, ci_subcategory.name, COUNT(DISTINCT price_history_this_week.product_id) as product_count, ci_subcategory.category_id
            FROM ci_subcategory
                LEFT JOIN price_history_this_week
                    ON ci_subcategory.id=price_history_this_week.subCategory_id
            WHERE price_history_this_week.history_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND price_history_this_week.history_date <= CURDATE()
                AND price_history_this_week.selling_price <= (price_history_this_week.cost_price * $maxPercent)
                AND price_history_this_week.selling_price >= (price_history_this_week.cost_price * $minPercent)
                AND price_history_this_week.selling_price > 0
                AND price_history_this_week.cost_price > 0
                AND price_history_this_week.stock=1
            $priceRangeQuery
            $stock_filter
            GROUP BY ci_subcategory.id
        ";
        $subCategories = $this->db->query($query)->result();

        return [
            $products, $totalCount->total_count, $brands, $categories, [$totalCount->max_percent, $totalCount->min_percent, $totalCount->max_price, $totalCount->min_price, $totalPrice->max_price, $totalPrice->min_price], $subCategories
        ];
    }

    public function get_this_week_discounts_products_category()
    {
        $query = "
            SELECT 
                price_
            FROM price_history
            WHERE price_history.history_date >= CURDATE() - INTERVAL 6 WEEK AND price_history.history_date <= CURDATE()
        ";

        $result = $this->db->query($query)->result();
        $countResult = $this->db->query($query)->row();
        return $countResult->total_count;
    }

    public function get_topdealsProduct()
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,
            CASE
                WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.selling_price, 2)
            END AS selling_price,
            ci_category.id as category_id,ci_category.name as category_name,ci_category.slug as category_slug");
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->where('ci_products.top_deal', '1');
        $this->db->limit(6);

        $this->db->group_by('ci_products.id');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_topdeals($orderBy = 'ASC', $limit = '')
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,product_visit.visit_count,
            CASE
                WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.selling_price, 2)
            END AS selling_price,
            CASE
                WHEN merchant_products.currency='Â£' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.cost_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.cost_price, 2)
            END AS cost_price,
            ci_category.id as category_id,ci_category.name as category_name,ci_category.slug as category_slug");
        $this->db->select('cost_price - selling_price AS discount', false);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->join('product_visit', 'product_visit.product_id = ci_products.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->where('ci_products.status', '1');
        // $this->db->where('merchant_products.selling_price >', 0.01);
        $this->db->order_by('product_visit.visit_count', $orderBy);
        if ($limit) {
            $this->db->limit($limit);
        }

        $this->db->group_by('ci_products.id');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getDiscountBySearch($searchkey)
    {
        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where("ci_products.status", '1');
        $this->db->where("merchant_products.selling_price >", 0);
        $this->db->like("ci_products.name", $searchkey);
        return $this->db->get()->row();
    }

    public function getBrandBySearchKey($searchkey, $categoryList, $minDiscount = '', $maxDiscount = '', $In_Stock = '', $minMaxPrice)
    {
        $this->db->select('Distinct(ci_products.brand_id) as brandId,ci_brand.id,ci_brand.brand_name, ci_brand.alias');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_brand', 'ci_products.brand_id = ci_brand.id', 'Left');
        $this->db->where("ci_products.status", '1');
        $this->db->where("merchant_products.selling_price >=", floor($minMaxPrice[0]->min_price));
        $this->db->where("merchant_products.selling_price <=", ceil($minMaxPrice[0]->max_price));
        $this->db->like("ci_products.name", $searchkey);
        $this->db->order_by("ci_brand.brand_name", "ASC");
        if (@$categoryList[0] > 0) {
            $this->db->where_in("ci_products.category_id", $categoryList);
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount . "'");
        }

        if ($In_Stock != '' && intval($In_Stock) > 0 && $In_Stock != null) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        $q = $this->db->get()->result();

        return $q;
    }

    public function getCategoryBySearchKey($searchkey, $brand = '', $minMaxPrice, $minDiscount = '', $maxDiscount = '', $In_Stock = '')
    {
        $this->db->select('Distinct(ci_products.category_id) as category_id, ci_category.id,ci_category.name, count(ci_products.id) as prd_cnt');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->where("ci_products.status", '1');
        $this->db->where("merchant_products.selling_price >=", floor($minMaxPrice[0]->min_price));
        $this->db->where("merchant_products.selling_price <=", ceil($minMaxPrice[0]->max_price));
        $this->db->like("ci_products.name", $searchkey);

        if ($brand != '' && count($brand) != 0 && $brand[0] != '') {
            $this->db->where_in('ci_products.brand_id', $brand);
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount . "'");
        }

        if ($In_Stock != '' && intval($In_Stock) > 0 && $In_Stock != null) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        $q = $this->db->get()->result();

        return $q;
    }

    public function getSearchId($searchkey)
    {
        $this->db->like('ci_brand.brand_name', $searchkey);
        $q = $this->db->get('ci_brand')->row();
        return $q->id;
    }

    public function getDiscount($id, $type)
    {
        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        if ($type == 'category') {
            $this->db->where("ci_products.category_id", $id);
        } else {
            $this->db->where("ci_products.brand_id", $id);
        }

        $this->db->where("merchant_products.selling_price >", 0);
        $q = $this->db->get()->row();
        return $q;
    }

    public function getDiscountByBrandIdList($brandIdList)
    {
        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');

        $this->db->where_in("ci_products.brand_id", $brandIdList);

        $this->db->where("merchant_products.selling_price >", 0);
        $q = $this->db->get()->row();
        return $q;
    }

    public function getDiscount_with_subcategory($brandIdList, $category, $subcategory, $min_price = "", $max_price = "", $In_Stock = "")
    {
        $min_p = str_replace(' ', '', str_replace('â‚¬', '', $min_price));
        $max_p = str_replace(' ', '', str_replace('â‚¬', '', $max_price));
        $fmax_p = (int) $max_p + 1;
        $fmin_p = (int) $min_p;
        $in_Stock = (int) $In_Stock;

        $cat = array_unique($category);

        unset($cat[0]);

        $new_category = [];

        foreach ($cat as $key => $value) {
            array_push($new_category, $value);
        }

        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        if ($in_Stock > 0 && $in_Stock != null) {
            $this->db->where('merchant_products.stock', $in_Stock);
        }
        $this->db->where_in("ci_products.brand_id", $brandIdList);
        $this->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
        if ($new_category[0] != "") {
            $this->db->where_in('ci_products.category_id', $new_category);
        }

        if ($subcategory[0] != "") {
            $this->db->where_in('ci_products.subCategory_id', $subcategory);
        }

        $q = $this->db->get()->row();

        //echo $this->db->last_query(); die;
        return $q;
    }

    public function getDiscountBySearch_with_subcategory($searchkey, $brand, $category, $min_price = "", $max_price = "", $In_Stock = "")
    {
        $min_p = str_replace(' ', '', str_replace('â‚¬', '', $min_price));
        $max_p = str_replace(' ', '', str_replace('â‚¬', '', $max_price));
        $fmax_p = (int) $max_p + 1;
        $fmin_p = (int) $min_p;
        $in_Stock = (int) $In_Stock;

        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        //$this->db->where("ci_products.status", '1');
        if ($in_Stock > 0 && $in_Stock != null) {
            $this->db->where('merchant_products.stock', $in_Stock);
        }
        //$this->db->where("ci_products.brand_id",$id);
        // $this->db->where('ci_products.category_id',$catid);
        $this->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
        if ($category[0] != "") {
            $this->db->where_in('ci_products.category_id', $category);
        }

        if ($brand[0] != "") {
            $this->db->where_in('ci_products.brand_id', $brand);
        }
        $this->db->like("ci_products.name", $searchkey);
        $q = $this->db->get()->row();

        // echo $this->db->last_query(); die;
        return $q;
    }

    public function getDiscountByCategory($id, $type)
    {

        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        //$this->db->where("ci_products.status", '1');
        if ($type == 'category') {

            $this->db->where("ci_products.category_id", $id);
        }
        // else{

        //     $this->db->where("ci_products.subCategory_id",$subcatid);
        // }
        // $this->db->where('ci_products.category_id',$catid);
        // $this->db->where("merchant_products.selling_price >", 0);
        $q = $this->db->get()->row();

        // echo $this->db->last_query(); die;
        return $q;
    }

    public function getDiscountBySubCategory($id, $subcatid, $type)
    {

        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        //$this->db->where("ci_products.status", '1');
        if ($type == 'category') {

            $this->db->where("ci_products.category_id", $id);
        } else {
            $this->db->where("ci_products.category_id", $id);

            $this->db->where("ci_products.subCategory_id", $subcatid);
        }
        // $this->db->where('ci_products.category_id',$catid);
        // $this->db->where("merchant_products.selling_price >", 0);
        $q = $this->db->get()->row();

        // echo $this->db->last_query(); die;
        return $q;
    }

    public function getDiscountBySubCategory_withbrand($id, $subcatid, $type, $brand, $min_price = "", $max_price = "", $In_Stock = "")
    {
        $min_p = str_replace(' ', '', str_replace('â‚¬', '', $min_price));
        $max_p = str_replace(' ', '', str_replace('â‚¬', '', $max_price));
        $fmax_p = (int) $max_p + 1;
        $fmin_p = (int) $min_p;
        $in_Stock = (int) $In_Stock;
        $this->db->select('max((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price * 100) as percent_discount');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        //$this->db->where("ci_products.status", '1');
        if ($in_Stock > 0 && $in_Stock != null) {
            $this->db->where('merchant_products.stock', $in_Stock);
        }
        if ($type == 'category') {

            $this->db->where("ci_products.category_id", $id);
            if (!empty($subcatid[0] > 1)) {
                $this->db->where_in("ci_products.subCategory_id", $subcatid);
            }
        } else {
            $this->db->where("ci_products.category_id", $id);
            if (!empty($subcatid[0] > 1)) {
                $this->db->where_in("ci_products.subCategory_id", $subcatid);
            }
        }
        if ($brand[0] != '') {

            $this->db->where_in('ci_products.brand_id', $brand);
        }
        $this->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
        $q = $this->db->get()->row();

        //echo $this->db->last_query(); die;
        return $q;
    }

    public function getBrandByCate($category_id = 0, $subcategory = 0, $all = 1, $min = '', $max = '', $minDiscount = '', $maxDiscount = '')
    {
        $this->db->select("ci_brand.id, ci_brand.brand_name, ci_brand.is_image, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage");
        $this->db->from('ci_brand');

        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Left');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');

        if ($category_id) {
            $this->db->where('ci_products.category_id', $category_id);
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount . "'");
        }

        if ($min !== null && $min !== '')
            $this->db->where('merchant_products.selling_price >', $min);

        if ($max !== null && $max !== '')
            $this->db->where('merchant_products.selling_price <', $max);

        if ($subcategory) {
            $array = explode(",", $subcategory);

            $subcategory_ids = array_map("intval", $array);

            $this->db->where_in('subCategory_id', $subcategory_ids);
        }

        $this->db->group_by('ci_brand.slug');
        $this->db->order_by('ci_brand.brand_name', 'ASC');

        if ($all == 1) {
            $result = $this->db->get()->result();
        } else {
            $result = $this->db->get()->row_array();
        }

        return $result;
    }

    public function getAllBrands($params = array())
    {
        $this->db->select('ci_brand.*');
        $this->db->from('ci_brand');
        $this->db->where('ci_brand.status', '1');
        $this->db->group_by('ci_brand.slug');
        if (array_key_exists("returnType", $params) && $params['returnType'] == 'count') {
            $result = $this->db->count_all_results();
        } else {
            if (array_key_exists("id", $params) || (array_key_exists("returnType", $params) && $params['returnType'] == 'single')) {
                if (!empty($params['id'])) {
                    $this->db->where('id', $params['id']);
                }
                $query = $this->db->get();
                $result = $query->row_array();
            } else {

                $this->db->order_by('ci_brand.slug', 'ASC');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params) && array_key_exists("page", $params)) {
                    $start = ($params['page'] - 1) * $params['limit'];
                    $this->db->limit($params['limit'], $start);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }

                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result() : false;
            }
        }
        return [];
    }

    public function allBrands($key)
    {
        $this->db->select('*');
        $this->db->from('ci_brand');
        $this->db->like('brand_name', $key, 'both');
        $query = $this->db->get()->result();
        //echo $this->db->last_query();die;
        return $query;
    }

    public function getBrandData($where = array(), $all = 1, $like = '')
    {
        $this->db->select("ci_brand.*,ci_brand.image as brandimage, ci_products.category_id, ci_products.subCategory_id as sub_category_id, ci_products.id as pid, merchant_products.id as mid,count(ci_products.id) as totalProduct");
        $this->db->from('ci_brand');
        //$this->db->join('cate_brand_relation','cate_brand_relation.brand_id = ci_brand.id','Left');
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        /*$this->db->join('merchant_products','merchant_products.name_wp = ci_products.name_wp','Right');*/

        if (!empty($where)) {
            $this->db->where($where);
        }
        if ($like != '') {
            $this->db->like('ci_brand.brand_name', $like, 'both');
        }
        $this->db->limit(5);
        $this->db->group_by('ci_brand.id');
        $this->db->order_by('ci_brand.slug', 'ASC');
        if ($all == 1) {
            $result = $this->db->get()->result();
        } else {
            $result = $this->db->get()->row_array();
        }
        //echo $this->db->last_query();die;
        return $result;
    }

    public function getBrandDataList($where = array(), $all = 1, $like = '', $catid)
    {
        $this->db->select("ci_brand.*,ci_brand.image as brandimage, ci_products.category_id, ci_products.subCategory_id as sub_category_id, ci_products.id as pid, merchant_products.id as mid,count(ci_products.id) as totalProduct");
        $this->db->from('ci_brand');
        //$this->db->join('cate_brand_relation','cate_brand_relation.brand_id = ci_brand.id','Left');
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');

        if (!empty($where)) {
            $this->db->where($where);
        }
        if ($like != '') {
            $this->db->like('ci_brand.brand_name', $like, 'both');
        }
        if (!empty($catid)) {
            $this->db->where('ci_products.category_id', $catid);
        }
        $this->db->group_by('ci_brand.id');
        $this->db->order_by('ci_brand.slug', 'ASC');
        $this->db->limit(5);
        if ($all == 1) {
            $result = $this->db->get()->result();
        } else {
            $result = $this->db->get()->row_array();
        }
        // echo $this->db->last_query();die;
        // echo "<pre>"; print_r($result); die;
        return $result;
    }

    public function getBrandCate($brandIdList)
    {
        $this->db->select("ci_category.id,ci_category.name, ci_category.name,ci_category.slug");
        $this->db->from('ci_category');
        $this->db->join('ci_products', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->where_in("ci_products.brand_id", $brandIdList);
        $this->db->where('ci_category.status', 1);
        $this->db->group_by('ci_category.id');
        $this->db->order_by('ci_category.name');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getDetails($slugname)
    {

        $this->db->where('slug', $slugname);
        $this->db->from('ci_category');
        $query = $this->db->get()->result();
        return $query;
    }

    public function getAllBrandList()
    {
        $this->db->select('ci_brand.*, COUNT(ci_products.id) as product_count');
        $this->db->from('ci_brand');
        $this->db->join('ci_products', 'ci_brand.id = ci_products.brand_id', 'left');
        $this->db->group_by('ci_brand.slug');
        $this->db->having('product_count >', 0);

        $this->db->order_by('ci_brand.slug', 'ASC');
        $result = $this->db->get()->result();

        return $result;
    }

    public function getBrandListByFilter($filterVal)
    {
        $this->db->select('ci_brand.*, COUNT(ci_products.id) as product_count');
        $this->db->from('ci_brand');
        $this->db->join('ci_products', 'ci_brand.id = ci_products.brand_id', 'left');
        $this->db->like('ci_brand.brand_name', $filterVal, 'after');
        $this->db->group_by('ci_brand.slug');
        $this->db->having('product_count >', 0);

        $this->db->order_by('ci_brand.slug', 'ASC');
        $result = $this->db->get()->result();

        return $result;
    }

    public function getBrandListBySearch($searchVal)
    {
        $this->db->select('*');
        $this->db->from('ci_brand');
        $this->db->like('ci_brand.brand_name', $searchVal);
        $this->db->group_by('ci_brand.slug');
        $q = $this->db->get()->result_array();
        return $q;
    }

    public function getBrandSubCateBySlugAndPrice($slug, $min, $max, $minDiscount = '', $maxDiscount = '')
    {
        $this->db->select("ci_subcategory.id, ci_subcategory.name, 'ci_subcategory.slug',ci_products.category_id, ci_products.subCategory_id as sub_category_id,ci_products.brand_id, count(DISTINCT ci_products.name_wp) as prd_cnt");
        $this->db->from('ci_brand');
        $this->db->where('ci_brand.slug', $slug);
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Right');
        $this->db->join('ci_subcategory', 'ci_products.subCategory_id = ci_subcategory.id', 'Right');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where('ci_subcategory.status', 1);
        $this->db->where('merchant_products.selling_price >=', $min);
        $this->db->where('merchant_products.selling_price <=', $max);

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount . "'");
        }

        $this->db->group_by('ci_subcategory.id');
        $this->db->order_by('ci_subcategory.name');
        $result = $this->db->get()->result();

        return $result;
    }

    public function getBrandSubCateBySlug($slug)
    {
        $this->db->select("ci_subcategory.id, ci_subcategory.name, 'ci_subcategory.slug',ci_products.category_id, ci_products.subCategory_id as sub_category_id,ci_products.brand_id, count(ci_products.id) as prd_cnt");
        $this->db->from('ci_brand');
        $this->db->where('ci_brand.slug', $slug);
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Right');
        $this->db->join('ci_subcategory', 'ci_products.subCategory_id = ci_subcategory.id', 'Right');
        $this->db->where('ci_subcategory.status', 1);
        $this->db->group_by('ci_subcategory.id');
        $this->db->order_by('ci_subcategory.name');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getBrandSubCate($where = array())
    {
        $this->db->select("ci_subcategory.id, ci_subcategory.name, 'ci_subcategory.slug',ci_products.category_id, ci_products.subCategory_id as sub_category_id,ci_products.brand_id");
        $this->db->from('ci_subcategory');
        $this->db->join('ci_products', 'ci_products.	subCategory_id = ci_subcategory.id', 'Left');
        $this->db->where($where);
        $this->db->where('ci_subcategory.status', 1);
        $this->db->group_by('ci_subcategory.id');
        $this->db->order_by('ci_subcategory.name');
        $result = $this->db->get()->result();
        return $result;
    }

    public function getCategorywithsub()
    {
        $this->db->select('c.id, c.name, c.slug, COUNT(p.id) as product_cnt', false);
        $this->db->from('ci_category c');
        $this->db->join('ci_subcategory s', 'c.id = s.category_id', 'left');
        $this->db->join('ci_products p', 's.id = p.subCategory_id', 'left');
        $this->db->having('product_cnt >', 0);
        $this->db->group_by('c.id');
        $query = $this->db->get();
        $data = array();

        foreach ($query->result() as $row) {
            $subcats = array();
            $this->db->select('s.name, s.slug, COUNT(p.id) as product_cnt', false);
            $this->db->from('ci_subcategory s');
            $this->db->join('ci_products p', 's.id = p.subCategory_id', 'left');
            $this->db->where('s.category_id', $row->id);
            $this->db->having('product_cnt >', 0);
            $this->db->group_by('s.id');
            $subcat_query = $this->db->get();

            foreach ($subcat_query->result() as $subcat_row) {
                $subcats[] = $subcat_row;
            }

            $data[] = array(
                "categoryName" => $row->name,
                "slug" => $row->slug,
                "product_cnt" => $row->product_cnt,
                "subCategories" => $subcats,
            );
        }

        return json_encode($data);
    }

    public function getBrandIdListFromSlug($slug)
    {
        $this->db->select(id);
        $this->db->from('ci_brand');
        $this->db->where("ci_brand.slug", $slug);

        $result = $this->db->get()->result();

        $idArray = [];

        foreach ($result as $object) {
            $idArray[] = $object->id;
        }

        return $idArray;
    }
}
