<?php

class Products_model extends CI_Model
{
    public function getProducts($subcatid = "", $catId = "", $type)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();
        $per_page = 12;

        $this->db->select('merchant_products.currency="$", ROUND(merchant_products.selling_price, 2) AS `selling_price`', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) AS cost_price', FALSE);

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');

        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');

        if ($type == 'category') {
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 1;
            $this->db->where('ci_products.category_id', $catId);
        } else if ($type == 'subcategory') {
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 1;
            $this->db->where('ci_products.category_id', $catId);
            $this->db->where('ci_products.subCategory_id', $subcatid);
        } else {
            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 1;
            $this->db->where('ci_brand.slug', $catId);
        }

        $this->db->order_by('selling_price', 'asc');
        $this->db->where('selling_price >', 0.01);

        if ($page == 1 || $page == 0) {
            $this->db->limit($per_page, 0);
        } else {
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        }
        $this->db->group_by('ci_products.name_wp');
        return $this->db->get()->result();
    }

    public function getProductsCounts($subcatid = "", $catId = "", $type)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'right');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');

        if ($type == 'category') {
            $this->db->where('ci_products.category_id', $catId);
        } else if ($type == 'subcategory') {
            $this->db->where('ci_products.category_id', $catId);
            $this->db->where('ci_products.subCategory_id', $subcatid);
        } else if ($type == 'brand') {
            $this->db->where('ci_brand.slug', $catId);
        }

        $this->db->where('selling_price >', 0.01);

        $this->db->order_by('selling_price', 'ASC');
        $this->db->group_by('ci_products.name_wp');
        return $this->db->count_all_results();
    }

    public function getFilterDataByBrandSearch($filter_data, $filterDataCount)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $per_page = 12;
        $categoryList = $filter_data['categoryList'];
        $subcatgoryList = $filter_data['subcatid'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $sort_by = $filter_data['sort_by'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $brandSlug = $filter_data['brandSlug'];
        $page = $filter_data['page'];
        $In_Stock = intval($filter_data['In_Stock']);

        if ($page * 12 > $filterDataCount) {
            $page = ceil($filterDataCount / 12);
        }

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug,ci_subcategory.id as subCategory_id,ci_subcategory.name as subCategory_name,ci_subcategory.slug as subCategory_slug,');
        $this->db->select('(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'right');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        
        $this->db->where('ci_brand.slug', $brandSlug);
        $category = array();
        $subcategory = array();
        foreach ($subcatgoryList as $Value) {
            $subcategory[] = $Value;
        }
        foreach ($categoryList as $categoryValue) {
            $category[] = $categoryValue;
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }
        if (!empty($sort_by)) {
            $this->db->order_by('selling_price', $sort_by);
        } else {
            $this->db->order_by('selling_price', 'ASC');
        }
        if ($category[0] > 1) {
            $this->db->where_in('ci_products.category_id', $category);
        }
        if ($subcategory[0] > 1) {
            $this->db->where_in('ci_products.subCategory_id', $subcategory);
        }
        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }
        if (!empty($page)) {
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        } else {
            $page = 1;
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        }
        $this->db->group_by('ci_products.name_wp');
        $q = $this->db->get()->result();
        return $q;
    }

    public function getFilterDataByBrandSearchCount($filter_data)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $categoryList = $filter_data['categoryList'];
        $subcatgoryList = $filter_data['subcatid'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $sort_by = $filter_data['sort_by'];
        $brandSlug = $filter_data['brandSlug'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $page = $filter_data['page'];
        $In_Stock = intval($filter_data['In_Stock']);
        /*echo "<pre>";
        print_r($filter_data);die;*/
        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug,ci_subcategory.id as subCategory_id,ci_subcategory.name as subCategory_name,ci_subcategory.slug as subCategory_slug,');
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'right');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $this->db->where('ci_brand.slug', $brandSlug);
        $category = array();
        $subcategory = array();
        foreach ($subcatgoryList as $Value) {
            $subcategory[] = $Value;
        }
        foreach ($categoryList as $categoryValue) {
            $category[] = $categoryValue;
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }
        if (!empty($sort_by)) {
            $this->db->order_by('selling_price', $sort_by);
        } else {
            $this->db->order_by('selling_price', 'ASC');
        }
        if ($category[0] > 1) {
            $this->db->where_in('ci_products.category_id', $category);
        }
        if ($subcategory[0] > 1) {
            $this->db->where_in('ci_products.subCategory_id', $subcategory);
        }
        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }
        $this->db->group_by('ci_products.name_wp');
        $q = $this->db->count_all_results();
        return $q;
    }

    public function getFilterDataBySearchKey($filter_data, $filterDataCount)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $per_page = 12;
        $categoryList = $filter_data['categoryList'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $sort_by = $filter_data['sort_by'];
        $brandid = $filter_data['brandId'];
        $searchkey = $filter_data['searchkey'];
        $page = $filter_data['page'];
        $In_Stock = intval($filter_data['In_Stock']);

        if ($page * 12 > $filterDataCount) {
            $page = ceil($filterDataCount / 12);
        }

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $this->db->select('(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'right');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $category = array();
        foreach ($categoryList as $categoryValue) {
            $category[] = $categoryValue;
        }
        $brands = array();
        foreach ($brandid as $brandValue) {
            $brands[] = $brandValue;
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        if (!empty($sort_by)) {
            $this->db->order_by('selling_price', $sort_by);
        } else {
            $this->db->order_by('selling_price', 'ASC');
        }

        if ($category != '' && count($category) > 0 && $category[0] != '') {
            $this->db->where_in('ci_products.category_id', $category);
        }
        if ($brandid != '' && count($brandid) != 0 && $brandid[0] != '') {
            $this->db->where_in('ci_brand.id', $brandid);
        }
        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }
        if (!empty($searchkey)) {
            $this->db->like('ci_products.name', $searchkey);
        }
        if (!empty($page)) {
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        } else {
            $page = 1;
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        }

        $this->db->group_by('ci_products.name_wp');

        $q = $this->db->get()->result();

        // echo $this->db->last_query();die;
        return $q;
    }

    public function getFilterDataBySearchKeyCount($filter_data)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $categoryList = $filter_data['categoryList'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $sort_by = $filter_data['sort_by'];
        $brandid = $filter_data['brandId'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $searchkey = $filter_data['searchkey'];
        $In_Stock = intval($filter_data['In_Stock']);

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $category = array();
        foreach ($categoryList as $categoryValue) {
            $category[] = $categoryValue;
        }
        $brands = array();
        foreach ($brandid as $brandValue) {
            $brands[] = $brandValue;
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        if (!empty($sort_by)) {
            $this->db->order_by('selling_price', $sort_by);
        } else {
            $this->db->order_by('selling_price', 'ASC');
        }
        if ($category != '' && count($category) > 0 && $category[0] != '') {
            $this->db->where_in('ci_products.category_id', $category);
        }
        if ($brands != '' && count($brands) != 0 && $brands[0] != '') {
            $this->db->where_in('ci_brand.id', $brands);
        }

        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }
        if (!empty($searchkey)) {
            $this->db->like('ci_products.name', $searchkey);
        }

        $this->db->group_by('ci_products.name_wp');

        $q = $this->db->count_all_results();
        return $q;
    }

    public function getFilterData($filter_data, $filterDataCount)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $per_page = 12;
        $catId = $filter_data['catid'];
        $subcatid = $filter_data['subcatid'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $sort_by = $filter_data['sort_by'];
        $In_Stock = intval($filter_data['In_Stock']);
        $brandList = $filter_data['brandList'];
        $page = $filter_data['page'];

        if ($page * 12 > $filterDataCount) {
            $page = ceil($filterDataCount / 12);
        }

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);

        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $this->db->where('ci_products.category_id', $catId);

        if (!empty($subcatid[0] > 1)) {
            $this->db->where_in('ci_products.subCategory_id', $subcatid);
        }

        $brands = array();
        foreach ($brandList as $brandValue) {
            $brands[] = $brandValue;
        }
        
        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        if ($brands[0] > 1) {
            $this->db->where_in('ci_products.brand_id', $brands);
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }

        if (!empty($sort_by)) {
            $this->db->order_by('selling_price', $sort_by);
        } else {
            $this->db->order_by('selling_price', 'ASC');
        }

        if (!empty($page)) {
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        } else {
            $page = 1;
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        }

        $this->db->group_by('ci_products.name_wp');

        $q = $this->db->get()->result();

        return $q;
    }

    public function getFilterDataCount($filter_data)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $catId = $filter_data['catid'];
        $subcatid = $filter_data['subcatid'];
        $min_price = $filter_data['min_price'];
        $max_price = $filter_data['max_price'];
        $minDiscount = $filter_data['from_discount'];
        $maxDiscount = $filter_data['to_discount'];
        $In_Stock = intval($filter_data['In_Stock']);
        $brandList = $filter_data['brandList'];

        $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

        $this->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);

        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $this->db->where('ci_products.category_id', $catId);

        if (!empty($subcatid[0] > 1)) {
            $this->db->where_in('ci_products.subCategory_id', $subcatid);
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $this->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        $brands = array();
        foreach ($brandList as $brandValue) {
            $brands[] = $brandValue;
        }

        if ($brands[0] > 1) {
            $this->db->where_in('ci_products.brand_id', $brands);
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $this->db->where('merchant_products.stock', $In_Stock);
        }

        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $this->db->having("selling_price >= " . $min_price);
            $this->db->having("selling_price <= " . $max_price);
        }

        $this->db->group_by('ci_products.name_wp');

        return $this->db->count_all_results();
    }

    public function getMinMaxPriceWithBrand($catid, $subcatid, $brandList)
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("
        MAX(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS max_price,
        MIN(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS min_price
    ");

        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where('ci_products.category_id', $catid);
        //$this->db->where("merchant_products.stock", '1');
        if ($subcatid != '') {

            $this->db->where('ci_products.subCategory_id', $subcatid);
        }
        foreach ($brandList as $brandValue) {

            $brands[] = $brandValue;
        }
        if ($brands[0] > 1) {

            $this->db->where_in('ci_products.brand_id', $brands);
        }

        $q = $this->db->get()->result();
        //echo $this->db->last_query(); die;
        return $q;
    }

    public function getMinMaxPriceByBrandSlug($brandSlug)
    {
        $exchangeRate = get_exchange_rate();
        $this->db->select("
        MAX(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS max_price,
        MIN(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS min_price
    ");
        $this->db->from('ci_brand');
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'right');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where('ci_brand.slug', $brandSlug);
        $q = $this->db->get()->result();
        return $q;
    }

    public function getMinMaxPriceByBrandId($brandId)
    {
        $exchangeRate = get_exchange_rate();
        $this->db->select("
        MAX(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS max_price,
        MIN(
            CASE
            WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
            WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
            ELSE ROUND(merchant_products.selling_price, 2)
            END
        ) AS min_price
    ");
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where('ci_products.brand_id', $brandId);
        $q = $this->db->get()->result();
        return $q;
    }

    public function getMinMaxPriceBySearchKey($searchkey)
    {
        $exchangeRate = get_exchange_rate();
        $this->db->select("
            MAX(
                CASE
                WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.selling_price, 2)
                END
            ) AS max_price,
            MIN(
                CASE
                WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                ELSE ROUND(merchant_products.selling_price, 2)
                END
            ) AS min_price
        ");
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        if (!empty($searchkey)) {
            $this->db->like('ci_products.name', $searchkey);
        }
        $q = $this->db->get()->result();
        return $q;
    }

    public function getProductsAjax($postData)
    {
        $per_page = 12;
        $searchkey = strtolower(@$postData['searchkey']);
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage, ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug");
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');

        if (!empty($searchkey)) {
            $this->db->like("ci_products.name", $searchkey);
        }

        $this->db->where('selling_price >', 0.01);

        if ($page == 1 || $page == 0) {
            $this->db->limit($per_page, 0);
        } else {
            $start = ($page - 1) * $per_page;
            $this->db->limit($per_page, $start);
        }

        $this->db->group_by('ci_products.name_wp');
        return $this->db->get()->result();
    }

    public function getProductsAjaxCount($postData)
    {
        $searchkey = strtolower(@$postData['searchkey']);

        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage");
        $this->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        if (!empty($searchkey)) {
            $this->db->like("ci_products.name", $searchkey);
        }

        $this->db->where('selling_price >', 0.01);

        $this->db->group_by('ci_products.name_wp');
        return $this->db->count_all_results();
    }

    public function getSubcategoriesName($subcategories)
    {
        $this->db->select('*');
        $this->db->from('ci_subcategory');
        $this->db->where_in('id', $subcategories);
        $q = $this->db->get()->result();
        return $q;
        $this->db->close();
    }

    public function getBrandsName($subcategories)
    {
        $this->db->select('brand_name as name,id');
        $this->db->from('ci_brand');
        $this->db->where_in('id', $subcategories);
        $q = $this->db->get()->result();
        return $q;
        $this->db->close();
    }

    public function getBrands($postData = array())
    {
        $filter_by = @$postData['filter_by'];
        $this->db->select("ci_brand.id, ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_products.id as pid, merchant_products.id as mid,count(ci_products.id) as totalProduct");
        $this->db->from('ci_brand');
        $this->db->join('ci_products', 'ci_products.brand_id = ci_brand.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        if (isset($filter_by) && $filter_by != 'empty') {
            $categories = array();
            $subcateparent = array();
            $subcategories = array();
            foreach ($filter_by as $value) {
                if ($value['key'] == 'categories' && !empty($value['value']) && $value['value'] != '') {
                    foreach ($value['value'] as $values) {
                        $categories[] = (int)$values;
                    }
                } elseif ($value['key'] == 'sub_categories' && !empty($value['value']) && $value['value'] != '') {
                    foreach ($value['value'] as $values) {
                        $subcateparent[] = (int)$values[0];
                        $subcategories[] = (int)$values[1];
                    }
                }
            }
        }
        if (!empty($categories)) {
            $this->db->group_start();
            if (!empty($subcategories)) {
                $onlyCate = array_diff($categories, $subcateparent);
                if (count($categories) > 1) {
                    foreach ($subcateparent as $key => $value) {
                        if ($key == 0) {
                            $this->db->where('ci_products.category_id', $value);
                            $this->db->where('ci_products.subCategory_id', $subcategories[$key]);
                        } else {
                            $this->db->or_where('ci_products.category_id', $value);
                            $this->db->where('ci_products.subCategory_id', $subcategories[$key]);
                        }
                    }
                } else {
                    $this->db->where('ci_products.category_id', $categories[0]);
                    $this->db->where_in('ci_products.subCategory_id', $subcategories);
                }
                if (is_array($onlyCate) && !empty($onlyCate)) {
                    $this->db->or_where_in('ci_products.category_id', $onlyCate);
                }
            } elseif (!empty($categories)) {
                $this->db->where_in('ci_products.category_id', $categories);
            }
            $this->db->group_end();
        }
        $this->db->group_by('ci_products.brand_id');
        $this->db->order_by('ci_brand.brand_name');
        $this->db->limit(5);
        $query = $this->db->get()->result();
        return $query;
    }

    public function getCompareProducts($compare = array())
    {
        $compare = array_unique($compare);
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage, merchant_products.options, merchant_products.merchant_store_url, merchant_products.created_at, ci_merchant.merchant_name, ci_merchant.shipping_cost,ci_category.slug as category_slug");
        $this->db->select('(selling_price / cost_price) * 100 AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'right');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'Left');
        foreach ($compare as $key => $value) {
            if ($key == 0) {
                $this->db->where('ci_products.id', $value);
            } else {
                $this->db->or_where('ci_products.id', $value);
            }
        }
        $this->db->group_by('ci_products.name_wp');
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_all_related_products($where, $orderBy = 'ASC', $limit = 5)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->select("ci_products.id,ci_products.slug,ci_products.name,ci_products.status,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.slug as category_slug, ci_category.name as category_name");
        $this->db->select('(selling_price / cost_price) * 100 AS percent_discount', FALSE);
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_products.category_id = ci_category.id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'Left');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'right');
        $this->db->where($where);
        $this->db->where("merchant_products.stock", '1');
        $this->db->where("selling_price >", 0);
        $this->db->limit($limit);
        $this->db->order_by('ci_products.id', $orderBy);
        $result = $this->db->get()->result();
        return $result;
    }

    public function get_master_product($id)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('merchant_products.selling_price as selling_price', FALSE);

        $this->db->select('merchant_products.cost_price as cost_price', FALSE);
        $this->db->select('ci_products.*, merchant_products.*, ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage, ci_category.name as category_name, ci_category.slug as category_slug, ci_subcategory.name as subcategory_name, ci_subcategory.slug as subcategory_slug, ci_merchant.shipping_cost, ci_merchant.shipping_days, ci_products.color, ci_products.size, ci_products.option');

        $this->db->select("ci_products.id,merchant_products.id as merchante_top_id,ci_products.slug,ci_products.name,merchant_products.stock, ci_products.image,ci_products.status,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_category.name as categoryname,ci_category.slug as categoryslug, ci_subcategory.name as subcategorynam,ci_subcategory.slug as subcategoryslug,ci_brand.slug as brandslug, ci_merchant.shipping_cost,ci_merchant.shipping_days");
        $this->db->select('(cost_price - selling_price) / cost_price * 100 AS percent_discount');
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'right');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'Left');
        $this->db->where('ci_products.id', $id);
        $this->db->where("merchant_products.stock", 1);
        $this->db->order_by('merchant_products.selling_price', 'asc');

        $result = $this->db->get()->result();
        $discount = 0;
        if (count($result) > 0) {
            $newarray = $result[0];
        } else {
            $this->db->select('ROUND(merchant_products.selling_price, 2) as selling_price', FALSE);
  
          $this->db->select('ROUND(merchant_products.cost_price, 2) as cost_price', FALSE);

            $this->db->select("ci_products.id,merchant_products.id as merchante_top_id,ci_products.slug,ci_products.name,merchant_products.stock, ci_products.image,ci_products.status,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_category.name as categoryname,ci_category.slug as categoryslug, ci_subcategory.name as subcategorynam,ci_subcategory.slug as subcategoryslug,ci_brand.slug as brandslug, ci_merchant.shipping_cost,ci_merchant.shipping_days");
            $this->db->select('(cost_price - selling_price) / cost_price * 100 AS percent_discount');
            $this->db->from('ci_products');
            $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
            $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
            $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
            $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'right');
            $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'Left');
            $this->db->where('ci_products.id', $id);

            //$this->db->where("ci_products.status", '1');
            $this->db->where("merchant_products.selling_price >", 0);
            //$this->db->where("merchant_products.stock", 1);

            $result = $this->db->get()->result();

            foreach ($result as $key => $value) {
                $newarray = $value;
                if ($discount < $value->percent_discount && $value->stock > 0) {
                    $newarray = $value;
                    $discount = $value->percent_discount;
                }
            }
        }
        return $newarray;
    }

    public function get_product_by_id($id)
    {
        // Currency conversion
        $exchangeRate = get_exchange_rate();

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->select("ci_products.id, merchant_products.id as merchante_top_id, ci_products.slug, ci_products.name, merchant_products.stock, ci_products.image, ci_products.status, ci_products.description, ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_category.name as categoryname, ci_category.slug as categoryslug, ci_subcategory.name as subcategorynam, ci_subcategory.slug as subcategoryslug, ci_brand.slug as brandslug, ci_merchant.shipping_cost, ci_merchant.shipping_days, ci_products.color, ci_products.size, ci_products.option");
        $this->db->select('(cost_price - selling_price) / cost_price * 100 AS percent_discount');
        $this->db->from('ci_products');
        $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'right');
        $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'Left');
        $this->db->where('ci_products.id', $id);
        $this->db->where("merchant_products.stock", 1);
        $this->db->group_by('selling_price');
        $this->db->order_by('merchant_products.selling_price', 'desc');
        $result = $this->db->get()->result();
        $discount = 0;
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $newarray = $value;
                if ($discount < $value->percent_discount && $value->stock > 0) {
                    $newarray = $value;
                    $discount = $value->percent_discount;
                }
            }
        } else {
            $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);
  
          $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

            $this->db->select("ci_products.id,merchant_products.id as merchante_top_id,ci_products.slug,ci_products.name,merchant_products.stock, ci_products.image,ci_products.status,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_category.name as categoryname,ci_category.slug as categoryslug, ci_subcategory.name as subcategorynam,ci_subcategory.slug as subcategoryslug,ci_brand.slug as brandslug, ci_merchant.shipping_cost,ci_merchant.shipping_days");
            $this->db->select('(cost_price - selling_price) / cost_price * 100 AS percent_discount');
            $this->db->from('ci_products');
            $this->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
            $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
            $this->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
            $this->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'right');
            $this->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'Left');
            $this->db->where('ci_products.id', $id);

            //$this->db->where("ci_products.status", '1');
            $this->db->where("merchant_products.selling_price >", 0);
            //$this->db->where("merchant_products.stock", 1);
            $this->db->group_by('merchant_products.selling_price');
            //$this->db->limit(1);

            $this->db->order_by('merchant_products.selling_price', 'desc');
            $result = $this->db->get()->result();

            foreach ($result as $key => $value) {
                $newarray = $value;
                if ($discount < $value->percent_discount && $value->stock > 0) {
                    $newarray = $value;
                    $discount = $value->percent_discount;
                }
            }
        }
        return $newarray;
    }

    public function get_option_merchant_products($name_wp)
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select('merchant_products.selling_price as selling_price', FALSE);

        $this->db->select('merchant_products.cost_price as cost_price', FALSE);

        $this->db->select('merchant_products.id as mproduct_id, merchant_products.stock, merchant_products.options, merchant_products.size, merchant_products.color, merchant_products.option, merchant_products.merchant_store_url, merchant_products.id as mpd, (selling_price / cost_price) * 100 AS percent_discount, ci_merchant.*', FALSE);
        $this->db->from('merchant_products');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        $this->db->where('merchant_products.name_wp', $name_wp);
        $this->db->order_by('stock', 'desc');
        $this->db->order_by('selling_price', 'asc');
        $products = $this->db->get()->result();
        foreach($products as $product){
            log_message('info', "$product->size : $product->selling_price");
        }
        log_message('info', "product : $products");
        usort($products, function ($a, $b) {
            $order = ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl'];
            $special_order = ['x small', 'small', 'medium', 'large', 'x large', 'xx large'];
            $sizes = ['tall xs', 'tall s', 'tall m', 'tall l', 'tall xl', 'tall 2xl', 'tall 3xl', 'tall 4xl', 'tall 5xl', 'tall 6xl', 'tall x', 'tall 1x', 'big 1x', 'big 2x', 'big 3x', 'big 4x', 'big 5x', 'big 6x'];
            if($a->stock != $b->stock) {
                return $b->stock - $a->stock;
            }else if ($a->selling_price == $b->selling_price) {
                if (is_numeric($a->size) && is_numeric($b->size)) {
                    return ($a->size < $b->size) ? -1 : 1;
                }
                if (is_numeric($a)) {
                    return -1;
                }

                if (is_numeric($b)) {
                    return 1;
                }
                $a_index = array_search(strtolower($a->size), $order);
                $b_index = array_search(strtolower($b->size), $order);

                $a_special_index = array_search(strtolower($a->size), $special_order);
                $b_special_index = array_search(strtolower($b->size), $special_order);

                $a_index_size = array_search(strtolower($a->size), $sizes);
                $b_index_size = array_search(strtolower($b->size), $sizes);
                if ($a_index !== false && $b_index !== false) {
                    return $a_index - $b_index;
                }

                if ($a_special_index !== false && $b_special_index !== false) {
                    return $a_special_index - $b_special_index;
                }
                if ($a_index_size !== false && $b_index_size !== false) {
                    return $a_index_size - $b_index_size;
                }

                if ($a_index !== false) {
                    return -1;
                }

                if ($b_index !== false) {
                    return 1;
                }

                if ($a_special_index !== false) {
                    return -1;
                }

                if ($b_special_index !== false) {
                    return 1;
                }

                if ($a_index_size !== false) {
                    return -1;
                }

                if ($b_index_size !== false) {
                    return 1;
                }
                //year - mos - yrs - years
                $pattern  = "/^(\d+(\.\d+)?(-\d+(\.\d+)?)?)\s+(YRS|MOS|Years|Year)$/i";
                if (preg_match($pattern, $a->size) && preg_match($pattern, $b->size)) {
                    $x = 0;
                    $y = 0;
                    if (stripos($a->size, 'mos') !== false) {
                        $x = floatval($a->size) / 12;
                    } elseif (stripos($a->size, '-') !== false) {
                        $x = str_ireplace(['years', 'year', 'yrs'], '', $a->size);
                        $x = array_sum(explode('-', $x)) / 2;
                    } else $x = floatval($a->size);

                    if (stripos($b->size, 'mos') !== false) {
                        $y = floatval($b->size) / 12;
                    } elseif (stripos($b->size, '-') !== false) {
                        $y = str_ireplace(['years', 'year', 'yrs'], '', $b->size);
                        $y = array_sum(explode('-', $y)) / 2;
                    } else {
                        $y = floatval($b->size);
                    }
                    if ($x != $y) {
                        return $x < $y ? -1 : 1;
                    }
                    return strcmp($a->size, $b->size);
                }
                //number-number
                if (preg_match('/^([\d.]+)-([\d.]+)$/', $a->size, $matches_a) && preg_match('/^([\d.]+)-([\d.]+)$/', $b->size, $matches_b)) {
                    return (($matches_a[1] + $matches_a[2]) / 2 <  ($matches_b[1] + $matches_b[2]) / 2) ? -1 : 1;
                }
                //number-string
                preg_match("/\d+(\.\d+)?/", $a->size, $matches_a);
                preg_match("/\d+(\.\d+)?/", $b->size, $matches_b);
                $number_a = floatval($matches_a[0]);
                $number_b = floatval($matches_b[0]);
                if ($number_a != $number_b) {
                    return $number_a < $number_b ? -1 : 1;
                } else {
                    return strcmp($a->size, $b->size);
                }
            }else return $a->selling_price < $b->selling_price ? -1 : 1;
        });
        return $products;
    }   

    public function get_merchant_products($id, $all = 'no')
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_products.id,merchant_products.id as merchante_top_id,ci_products.slug,ci_products.name, ci_merchant.*, ci_merchant.image as eu_icon, merchant_products.merchant_store_url");
        $this->db->select('(selling_price / cost_price) * 100 AS percent_discount', FALSE);

        $this->db->select('ROUND(merchant_products.selling_price) as selling_price', FALSE);

        $this->db->select('ROUND(merchant_products.cost_price) as cost_price', FALSE);

        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');
        $this->db->where('ci_products.id', $id);
        $this->db->order_by('merchant_products.stock', 'desc');
        $this->db->order_by('merchant_products.selling_price', 'asc');
        $this->db->group_by('selling_price');

        if ($all == 'yes')
            return $this->db->get()->result();
        $result = $this->db->get()->row();
        
        return $result;
    }

    public function update_data($table, $where, $data)
    {
        $this->db->where($where);
        $already = $this->db->get($table)->result();
        if (!empty($already)) {
            $this->db->where($where);
            $this->db->update($table, $data);
            return true;
        }
        $this->db->insert($table, $data);
        return true;
    }

    public function getProductByBrand($id)
    {
        $this->db->select('*');
        $this->db->from('ci_products');
        $this->db->where('brand_id', $id);
        $this->db->limit(15);
        return $this->db->get()->result();
    }

    public function get_meta_details($type)
    {
        $this->db->where('meta_type', $type);
        $q = $this->db->get('ci_meta_tags')->row();
        return $q;
    }

    public function getFilterBrandName($brandList)
    {
        $this->db->select('id, alias');
        $this->db->from('ci_brand');
        $this->db->where_in('id', $brandList);
        $q = $this->db->get()->result();
        return $q;
    }

    public function getFilterSubcategoryName($brandList)
    {
        $this->db->select('id,name');
        $this->db->from('ci_subcategory');
        $this->db->where_in('id', $brandList);
        $q = $this->db->get()->result();
        return $q;
    }

    public function getCategoryList($categoryList)
    {
        $this->db->select('id,name');
        $this->db->from('ci_category');
        $this->db->where_in('id', $categoryList);
        $q = $this->db->get()->result();
        return $q;
    }

    public function getPriceDrop($id, $mid)
    {
        $this->db->select('id, selling_price');
        $this->db->from('price_history_this_week');
        $this->db->where('product_id', $id);
        $r = $this->db->get();
        $count = $r->num_rows();

        if ($count == 0) return "";
        $res = $r->row();
        $price = floatval($res->selling_price);
        $this->db->select('history_date');
        $this->db->from('price_history_temp');
        $this->db->where('product_id', $mid);
        $this->db->where("selling_price > $price");
        $this->db->order_by('history_date', 'DESC');
        $this->db->limit(1);
        $r = $this->db->get();
        $row = $r->row();
        if ($row)
            return $row->history_date;
        return "";
    }
}
