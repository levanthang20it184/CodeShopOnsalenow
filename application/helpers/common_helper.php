<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function dd($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    die();
}

function qry()
{
    $ci = &get_instance();
    echo $ci->db->last_query();
    die();
}

function abort($error = 404)
{
    redirect(base_url('frontend/home/error_404'));
}

function menu_data()
{
    $ci = &get_instance();
    $ci->db->where('status', 1);
    $ci->db->limit(5);
    return $ci->db->get('ci_menu')->result();
}

function getProductImage($id, $image)
{
    $ci = &get_instance();
    $ci->db->from('cdn_images');
    $ci->db->where('ci_product_id', $id);
    $ci->db->limit(1);
    $result = $ci->db->get()->result_array();
    if (isset($result[0]['filepath']))
        return "https://onsalenow99.b-cdn.net".$result[0]['filepath'];
    return showImage($ci->config->item('product'), $image);
}

function web_logo()
{
    $ci = &get_instance();
    $ci->db->select('*');
    $ci->db->where('id', 1);
    $query = $ci->db->get('ci_general_settings');
    return $query->row();

}

function banner_screen($banner_position = null)
{
    $ci = &get_instance();
    $ci->db->where('banner_position', $banner_position);
    return $ci->db->get('ci_banners')->row();
}

function fetch_data($table, $where, $column = '*')
{
    $ci = &get_instance();
    $ci->db->select($column);
    $ci->db->where($where);
    $row = $ci->db->get($table)->row_array();
    if ($row && isset($row[$column])) {
        return $row[$column];
    } else {
        return '';
    }
}


function showImage($folder = '', $image = '')
{
    $ci = &get_instance();
    if ($image && !empty($image) && $folder==$ci->config->item('product')) {
        $ci->db->from('ci_products');
        $ci->db->where('image', $image);
        $ci->db->limit(1);
        $result = $ci->db->get()->result_array();
        if (isset($result[0]['image'])) {
            $ci->db->from('cdn_images');
            $ci->db->where('ci_product_id', $result[0]['id']);
            $ci->db->limit(1);
            $result = $ci->db->get()->result_array();
            if (isset($result[0]['filepath']))
                return "https://onsalenow99.b-cdn.net".$result[0]['filepath'];
        }
    }
    if ($folder == '' && $image == '') {
        return base_url("uploads/dummy.jpg");
    }
    $file = $folder . '/' . $image;

    if (filter_var($image, FILTER_VALIDATE_URL)) {
        return $image;
    }
    if ($image && file_exists($file)) {
        return $file;
    }

    return $folder . '/default.png';
}

function showPrice($price, $cost = 0)
{
    $priceShow = "<h5>&euro;" . (is_numeric($price) && floor($price) == $price ? intval($price) : $price);
    if ($cost > 0 && $cost != $price && $cost > $price) {
        $priceShow .= "<del>&euro;" . (is_numeric($cost) && floor($cost) == $cost ? intval($cost) : $cost) . "</del>";
    }
    $priceShow .= "</h5>";
    echo $priceShow;
}

function chechProducts($id)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock', '1');
    //$ci->db->where('ci_products.status','1');
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->where('ci_products.category_id', $id);
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    return $count;
}

// function chechProductsBySubCategory($id, $subid = "")
// {
    
//     $ci = &get_instance();
//     $ci->db->select("ci_products.name as pname");
//     $ci->db->from('ci_products');
//     $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
//     $ci->db->where('merchant_products.stock >', 0);
//     $ci->db->where('merchant_products.selling_price >', 0.01);
//     $ci->db->where('ci_products.subCategory_id', $id);
//     if (!empty($subid = "")) {
//         $ci->db->where('ci_products.category_id', $subid);
       
//     }
//     //$ci->db->where('merchant_products.stock', '1');
//     //$ci->db->where('ci_products.status', '1');
//     $ci->db->group_by('merchant_products.name_wp');
//     $count = $ci->db->count_all_results();
//     //print_r($ci->db->last_query());
//     return $count;
// }
// change đoạn code trên cho đồng bộ
function chechProductsBySubCategory($id, $subid = "")
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->where('ci_products.subCategory_id', $id);
    if (!empty($subid)) {   
        $ci->db->where('ci_products.category_id', $subid);
    }
    $ci->db->group_by('ci_products.name');
    
    $query = $ci->db->get();
    $count = $query->num_rows();
    
    return $count;
}


function chechProductsBySubCategoryWith_price($id, $subid, $min_price, $max_price, $In_Stock, $minDiscount = '', $maxDiscount = '', $brand_id)
{
    $in_Stock = (int)$In_Stock;
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock >', 0);
    if ($in_Stock > 0 && $in_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $in_Stock);
    }

    if ($min_price != 'null' && $max_price != 'null') {
        $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
        $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
        $fmax_p = (int)$max_p + 1;
        $fmin_p = (int)$min_p;
        $ci->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
    }
 
    $ci->db->where('ci_products.subCategory_id', $id);
    if ($subid !== "") {
        $ci->db->where('ci_products.category_id', $subid);
    }

    if (!empty($brand_id[0] > 1)) {

        $ci->db->where_in('ci_products.brand_id', $brand_id);
    }

    if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
    }

    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();

    return $count;
}


function getProductsByCategory($catid, $brand_id)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock >', 0);
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->where('ci_products.category_id', $catid);
    $ci->db->where('ci_products.brand_id', $brand_id);
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // $ci->db->last_query(); die;
    return $count;
}

function getProductsBySubCategory($catid, $brand_id)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock >', 0);
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->where('ci_products.subCategory_id', $catid);
    $ci->db->where('ci_products.brand_id', $brand_id);
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // $ci->db->last_query(); die;
    return $count;
}

function getProductsBySubCategoryWith_price($catid, $brand_id, $min_price, $max_price, $In_Stock)
{
    $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
    $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
    $fmax_p = (int)$max_p + 1;
    $fmin_p = (int)$min_p;
    $in_Stock = (int)$In_Stock;
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    if ($in_Stock > 0 && $in_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $in_Stock);
    }

    $ci->db->where('ci_products.subCategory_id', $catid);
    $ci->db->where('ci_products.brand_id', $brand_id);
    $ci->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // $ci->db->last_query(); die;
    return $count;
}

function getProductsByCategorySearchKey($catid, $searchkey)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock >', 0);
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->where('ci_products.category_id', $catid);
    $ci->db->like('ci_products.name', $searchkey);
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // $ci->db->last_query(); die;
    return $count;
}

function getProductsByCategorySearchKeyWith_price($catid, $searchkey, $min_price, $max_price, $In_Stock, $minDiscount = '', $maxDiscount = '')
{
    $ci = &get_instance();
    $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
    $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
    $fmax_p = (int)$max_p + 1;
    $fmin_p = (int)$min_p;
    $in_Stock = (int)$In_Stock;
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.stock >', 0);
    if ($in_Stock > 0 && $in_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $in_Stock);
    }
    $ci->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
    $ci->db->where('ci_products.category_id', $catid);

    if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
    }

    $ci->db->like('ci_products.name', $searchkey);
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // $ci->db->last_query(); die;
    return $count;
}

function checkProductsByDiscount($discount)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $discount . "' AND '" . ($discount+9) ."'");
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    // echo  $ci->db->last_query(); die;
    return $count;

}

function chechProductsByBrandSub($catid, $subcatid, $id)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.selling_price >', 0.01);
    //$ci->db->where('ci_products.status', '1');
    //$ci->db->where('merchant_products.stock', '1');
    $ci->db->where('ci_products.brand_id', $id);

    if ($subcatid != '') {
        $ci->db->where('ci_products.subCategory_id', $subcatid);
    }
    $ci->db->where('ci_products.category_id', $catid);
    $ci->db->group_by('merchant_products.name_wp');
    //$result = $ci->db->get()->result_array();
    //print_r($ci->db->last_query());die;
    $count = $ci->db->count_all_results();
    //print_r($count);die;
    return $count;

}

function chechProductsByBrandSubWith_price($catid, $subcatid, $id, $min_price, $max_price, $In_Stock, $minDiscount, $maxDiscount)
{
    $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
    $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
    $fmax_p = (int)$max_p + 1;
    $fmin_p = (int)$min_p;
    $in_Stock = (int)$In_Stock;
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    if ($in_Stock > 0 && $in_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $in_Stock);
    }
    //$ci->db->where('ci_products.status', '1');
    //$ci->db->where('merchant_products.stock', '1');
    $ci->db->where('ci_products.brand_id', $id);

    $ci->db->where('ci_products.subCategory_id', $subcatid);
    
    $ci->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
    $ci->db->where('ci_products.category_id', $catid);

    if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
    }

    $ci->db->group_by('merchant_products.name_wp');
    //$result = $ci->db->get()->result_array();
    //print_r($ci->db->last_query());die;
    $count = $ci->db->count_all_results();

    //print_r($count);die;
    return $count;
}

function chechProductsByBrandSearch($brandId, $searchkey, $min, $max)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('ci_products.status', '1');
    $ci->db->where("merchant_products.selling_price >=", $min);
    $ci->db->where("merchant_products.selling_price <=", $max);
    $ci->db->where('ci_products.brand_id', $brandId);
    $ci->db->like('ci_products.name', $searchkey);

    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();

    // echo $ci->db->last_query();

    return $count;

}

function chechProductsByBrandSearchWith_price($brandId, $searchkey, $min_price, $max_price, $In_Stock, $categoryList, $minDiscount = '', $maxDiscount = '')
{
    $ci = &get_instance();
    $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
    $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
    $fmax_p = (int)$max_p + 1;
    $fmin_p = (int)$min_p;
    $in_Stock = (int)$In_Stock;
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where("merchant_products.selling_price BETWEEN '" . $fmin_p . "' AND $fmax_p");
    if ($in_Stock > 0 && $in_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $in_Stock);
    }

    $ci->db->where('ci_products.brand_id', $brandId);
    if ($categoryList[0] != "") {
        $ci->db->where_in('ci_products.category_id', $categoryList);
    }

    if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
    }

    $ci->db->like('ci_products.name', $searchkey);

    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    return $count;

}

function chechProductsByBrand($catid, $id)
{
    $ci = &get_instance();
    $ci->db->select("ci_products.name as pname");
    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('merchant_products.selling_price >', 0.01);
    //$ci->db->where('ci_products.status', '1');
    $ci->db->where('ci_products.brand_id', $id);
    if (is_null($catid)) {

    } else {
        $ci->db->where('ci_products.category_id', $catid);
    }

    // if($subcatid!=''){
    //     $ci->db->where('ci_products.subCategory_id', $subcatid);
    // }
    $ci->db->group_by('merchant_products.name_wp');
    $count = $ci->db->count_all_results();
    return $count;

}

function chechProductsByBrandWith_price($catId, $subcatid, $id, $min_price, $max_price, $In_Stock, $minDiscount = '', $maxDiscount = '')
{
        $exchangeRate = get_exchange_rate();

        $ci = &get_instance();

        $ci->db->select('CAST(IF(merchant_products.currency="$", ROUND(merchant_products.selling_price*' . $exchangeRate[1] . ', 2),
        IF(merchant_products.currency="£", ROUND(merchant_products.selling_price*' . $exchangeRate[0] . ', 2),
        IF(merchant_products.currency="&euro;", merchant_products.selling_price,
        0))) AS DECIMAL(10,2)) as selling_price', FALSE);

        $ci->db->select('CAST(IF(merchant_products.currency="$", ROUND(merchant_products.cost_price*' . $exchangeRate[1] . ', 2),
        IF(merchant_products.currency="£", ROUND(merchant_products.cost_price*' . $exchangeRate[0] . ', 2),
        IF(merchant_products.currency="&euro;", merchant_products.cost_price,
        "0.00"))) AS DECIMAL(10,2)) as cost_price', FALSE);

        $ci->db->select('ci_products.id,ci_products.slug,ci_products.name,ci_products.image,ci_products.description,ci_brand.brand_name, ci_brand.alias, ci_brand.is_image, ci_brand.image as brandimage,ci_category.id as category_id,ci_category.name as category_name, ci_category.slug as category_slug');
        $ci->db->select('(((cost_price - selling_price) / cost_price) * 100) AS percent_discount', FALSE);

        $ci->db->from('ci_products');
        $ci->db->join('ci_brand', 'ci_brand.id = ci_products.brand_id', 'Left');
        $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', 'left');
        $ci->db->join('ci_merchant', 'ci_merchant.id = merchant_products.merchant_id', 'left');
        $ci->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'Left');
        $ci->db->join('ci_subcategory', 'ci_subcategory.id = ci_products.subCategory_id', 'Left');
        $ci->db->where('ci_products.category_id', $catId);
        $ci->db->where('ci_products.brand_id', $id);

        if (!empty($subcatid[0] > 1)) {
            $ci->db->where_in('ci_products.subCategory_id', $subcatid);
        }

        if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
            $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
        }

        if ($In_Stock > 0 && $In_Stock != NULL) {
            $ci->db->where('merchant_products.stock', $In_Stock);
        }

        if (is_numeric($min_price) && is_numeric($max_price) && $min_price >= 0 && $max_price) {
            $ci->db->where("merchant_products.selling_price >= " . $min_price);
            $ci->db->where("merchant_products.selling_price <= " . $max_price);
        }

        $ci->db->group_by('ci_products.name_wp');

        $result = count($ci->db->get()->result());
        return $result;

    // $in_Stock = (int)$In_Stock;
    // $ci = &get_instance();
    // $ci->db->select("ci_products.name as pname");
    // $ci->db->from('ci_products');
    // $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    // if ($in_Stock > 0 && $in_Stock != NULL) {
    //     $ci->db->where('merchant_products.stock', $in_Stock);
    // }
    // //$ci->db->where('ci_products.status', '1');
    // $ci->db->where('ci_products.brand_id', $id);
    // $ci->db->where('ci_products.category_id', $catid);
    // if (!empty($subcatid[0] > 1)) {
    //     $ci->db->where_in('ci_products.subCategory_id', $subcatid);
    // }

    // if ($min_price != 'null' && $max_price != 'null') {
    //     $min_p = str_replace(' ', '', str_replace('&euro;', '', $min_price));
    //     $max_p = str_replace(' ', '', str_replace('&euro;', '', $max_price));
    //     $fmax_p = (int)$max_p + 1;
    //     $fmin_p = (int)$min_p;
    
    //     $ci->db->where("merchant_products.selling_price between '" . $fmin_p . "' AND $fmax_p");
    // }

    // if ($discount != "") {
    //     foreach ($discount as $discountValue) {
    //         $ci->db->where('(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) >=', $discountValue);
    //     }
    // }

    // $ci->db->group_by('ci_products.name_wp');
    // $count = $ci->db->count_all_results();

    // return $count;
}

function getMinMaxPrice($catid, $subcatid, $brand = '', $minDiscount = '', $maxDiscount = '', $In_Stock = '', $searchkey = '')
{
    $exchangeRate = get_exchange_rate();

    $ci = &get_instance();

    $ci->db->select("
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

    $ci->db->from('ci_products');
    $ci->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
    if (!empty($searchkey)) {
        $ci->db->like('ci_products.name', $searchkey);
    }

    if ($catid != '' && count($catid) > 0 && $catid[0] != '') {
        $ci->db->where_in('ci_products.category_id', $catid);
    }
    if ($subcatid != '' && count($subcatid) > 0 && $subcatid[0] != '') {
        $ci->db->where_in('ci_products.subCategory_id', $subcatid);
    }
    if ($brand != '' && count($brand) != 0 && $brand[0] != '') {
        $ci->db->where_in('ci_products.brand_id', $brand);
    }

    if ($minDiscount !== null && $minDiscount !== '' && $minDiscount !== 'null' && $maxDiscount !== null && $maxDiscount !== '' && $maxDiscount !== 'null') {
        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $minDiscount . "' AND '" . $maxDiscount ."'");
    }

    if ($In_Stock != '' && intval($In_Stock) > 0 && $In_Stock != NULL) {
        $ci->db->where('merchant_products.stock', $In_Stock);
    }

    $q = $ci->db->get()->result();

    return $q;
}

function getSubcategory($id)
{
    $ci = &get_instance();
    $ci->db->select("ci_subcategory.*, ci_products.id as pid, merchant_products.id as mid");
    $ci->db->from('ci_subcategory');
    $ci->db->join('ci_products', 'ci_products.subCategory_id = ci_subcategory.id', 'Left');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('ci_subcategory.category_id', $id);
    $ci->db->where('ci_subcategory.status', '1');
    //$ci->db->where('ci_products.status', '1');
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->order_by('ci_subcategory.name', 'ASC');
    $ci->db->group_by('ci_subcategory.id');
    $count = $ci->db->get()->result();

    return $count;
}

function getMinPrice()
{
    $min_price_row = DB::table('merchant_products')
        ->select('*')
        ->where('price', '=', DB::table('price_table')->min('price'))
        ->get();


    $ci = &get_instance();
    $ci->db->select("ci_subcategory.*, ci_products.id as pid, merchant_products.id as mid");
    $ci->db->from('ci_subcategory');
    $ci->db->join('ci_products', 'ci_products.subCategory_id = ci_subcategory.id', 'Left');
    $ci->db->join('merchant_products', 'merchant_products.name_wp = ci_products.name_wp', "Right");
    $ci->db->where('ci_subcategory.category_id', $id);
    $ci->db->where('ci_subcategory.status', '1');
    //$ci->db->where('ci_products.status', '1');
    $ci->db->where('merchant_products.selling_price >', 0.01);
    $ci->db->order_by('ci_subcategory.name', 'ASC');
    $ci->db->group_by('ci_subcategory.id');
    $count = $ci->db->get()->result();

    return $count;
}
function get_exchange_rate()
{
    $gbpRate = 0.88572; // Hardcoded GBP rate
    $usdRate = 1.10185; // Hardcoded USD rate

    return [$gbpRate, $usdRate];
}
// function get_exchange_rate()
// {
//     $ci = &get_instance();
//     $base_currency = 'EUR';
//     $target_currencies = ['USD', 'GBP'];
//     $ci->db->from("exchange_rates");
//     $result = $ci->db->get()->result_array();
//     if (count($result) > 0) {
//         $latest_created_at = strtotime($result[0]['created_at']);
//         $now = time();
//         if (($now - $latest_created_at) > (60 * 60 * 12)) {
//             get_exchange_rate_from_api($base_currency, $target_currencies);
//             $ci->db->from("exchange_rates");
//             $result = $ci->db->get()->result_array();
//         }
//     } else {
//         $result = get_exchange_rate_from_api($base_currency, $target_currencies);
//         if ($result) {
//             $ci->db->from("exchange_rates");
//             $result = $ci->db->get()->result_array();
//         } else {
//             return [0.88572, 1.10185];
//         }
//     }
//     $gbpRate = null;
//     $usdRate = null;
//     foreach ($result as $rate) {
//         if ($rate['target_currency'] === 'GBP') {
//             $gbpRate = $rate['exchange_rate'];
//         } elseif ($rate['target_currency'] === 'USD') {
//             $usdRate = $rate['exchange_rate'];
//         }
//     }
//     return [$gbpRate, $usdRate];
// }



function get_exchange_rate_from_api($base_currency, $target_currencies)
{
    $ci = &get_instance();

    $params = array(
        'symbols' => 'GBP,USD',
        'base' => 'EUR'
    );
    $queryString = http_build_query($params);
    $url = 'https://api.apilayer.com/exchangerates_data/latest?' . $queryString;
    $headers = array(
        'Content-Type: text/plain',
        'apikey: EXKLkLnTZ7ni5xnNBQSwh2z4QskaAuij'
    );
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET'
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    $json_response = json_decode($response, true);
    $data = [];

    foreach ($json_response['rates'] as $target => $rate) {
        // Check if the row exists
        $existing_row = $ci->db->get_where('exchange_rates', array('base_currency' => $base_currency, 'target_currency' => $target))->row();

        if ($existing_row) {
            // Update the existing row
            $data = [
                'exchange_rate' => $rate,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $ci->db->where('id', $existing_row->id)->update('exchange_rates', $data);
        } else {
            // Insert a new row
            $data = [
                'base_currency' => $base_currency,
                'target_currency' => $target,
                'exchange_rate' => $rate,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $ci->db->insert('exchange_rates', $data);
        }
    }

    return $json_response['success'];
}

function loadPriceHistory($productId) {
    $ci = &get_instance();

    $ci->db->where('history_date >=', date('Y-m-d', strtotime('-1 year')));
    $ci->db->where('product_id', $productId);
    return $ci->db->get('price_history')->result_array();
}

function getDiscountRangeList($brandIdList, $categoryId, $subCategoryIdList, $minPrice = "", $maxPrice = "", $inStock = "")
{
    $ci = &get_instance();

    $inStock = (int) $inStock;
    $minPrice = (int) (str_replace(' ', '', str_replace('&euro;', '', $minPrice)));
    $maxPrice = (int) str_replace(' ', '', str_replace('&euro;', '', $maxPrice)) + 1;

    $discountRangeList = [];

    for ($i = 10; $i <= 90; $i+=10) {
        $ci->db->from('ci_products');
        $ci->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $ci->db->join('ci_merchant', 'merchant_products.merchant_id = ci_merchant.id', 'Left');

        if ($inStock !== null && $inStock > 0) {
            $ci->db->where('merchant_products.stock', $inStock);
        }
        
        $ci->db->where("merchant_products.selling_price BETWEEN '" . $minPrice . "' AND $maxPrice");
        
        if ($brandIdList !== '' && count($brandIdList) !== 0 && $brandIdList[0] !== '') {
            $ci->db->where_in('ci_products.brand_id', $brandIdList);
        }

        if ($categoryId !== 0) {
            $ci->db->where("ci_products.category_id", $categoryId);
        }

        if ($subCategoryIdList !== '' && count($subCategoryIdList) !== 0 && $subCategoryIdList[0] !== '') {
            $ci->db->where_in('ci_products.subCategory_id', $subCategoryIdList);
        }

        $ci->db->where("(((merchant_products.cost_price - merchant_products.selling_price) / merchant_products.cost_price) * 100) BETWEEN '" . $i . "' AND '" . ($i+9) ."'");

        $discountRangeList[] = [$i, $ci->db->count_all_results()];
    }

    return $discountRangeList;
}

function getPriceDrop($id)
{
    $ci = &get_instance();
    $ci->db->select('id, selling_price');
    $ci->db->from('price_history_this_week');
    $ci->db->where('product_id', $id);
    $r = $ci->db->get();
    $count = $r->num_rows();

    if ($count == 0) return "";

    $res = $r->result();
    $price = $res[0]->selling_price;

    $ci->db->select('merchant_products.id');
    $ci->db->from('ci_products');
    $ci->db->where('ci_products.id', $id);
    $ci->db->join('merchant_products', 'merchant_products.name_wp=ci_products.name_wp', 'left');
    $ci->db->limit(1);
    $mres = $ci->db->get()->result();
    $mid = $mres[0]->id;

    $ci->db->select('history_date');
    $ci->db->from('price_history_temp');
    $ci->db->where('product_id', $mid);
    $ci->db->where('selling_price >', $price);
    $ci->db->order_by('history_date', 'DESC');
    $ci->db->limit(1);
    $r = $ci->db->get();
    $row = $r->row();
    $last_query = $ci->db->last_query();
    if ($row)
        return $row->history_date;
    return "";
}


?>