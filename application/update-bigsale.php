<?php
// Assuming CI's index.php sets up the environment and returns the CI instance
// Adjust the path to where your CI's index.php or equivalent bootstrap file is located
require_once '../index.php';

// Alternatively, if you have a specific way to get the CI instance, adjust accordingly
$CI =& get_instance();

function update_bigsale_products_db($CI, $sort_by = 'asc') {
    set_time_limit(50000);
    // Xóa dữ liệu cũ
    $CI->db->truncate('daily_bigsale_products');
    
    // Lấy dữ liệu mới từ các bảng liên quan với sắp xếp thích hợp
    $CI->db->select("
        p.id AS product_id,
        p.slug,
        p.name,
        p.image,
        b.alias AS brand_name,
        b.image AS brand_image,
        mp.selling_price,
        mp.cost_price,
        ROUND((1 - mp.selling_price / mp.cost_price) * 100, 2) AS discount_percentage,
        c.id AS category_id,
        c.name AS category_name,
        c.slug AS category_slug,
        sc.id AS subcategory_id,
        sc.name AS subcategory_name,
        mp.stock AS in_stock
    ");
    $CI->db->from('ci_products p');
    $CI->db->join('ci_brand b', 'p.brand_id = b.id', 'left');
    $CI->db->join('ci_category c', 'p.category_id = c.id', 'left');
    $CI->db->join('ci_subcategory sc', 'p.subCategory_id = sc.id', 'left');
    $CI->db->join('merchant_products mp', 'mp.name_wp = p.name_wp', 'left');
    $CI->db->where("(100 - ROUND(mp.selling_price / mp.cost_price * 100, 2)) BETWEEN 50 AND 99", NULL, FALSE);    
    $CI->db->where('mp.cost_price <>', 0);
    $CI->db->where('mp.selling_price <>', 0);
    $CI->db->where('mp.stock', 1);

    // Sắp xếp theo điều kiện
    if ($sort_by == "asc") {
        $CI->db->order_by('mp.selling_price', 'ASC');
    } elseif ($sort_by == "desc") {
        $CI->db->order_by('mp.selling_price', 'DESC');
    } else {
        $CI->db->order_by('mp.selling_price / mp.cost_price', 'ASC', FALSE);
    }

    $query = $CI->db->get();
    
    // Kiểm tra nếu có lỗi trong truy vấn
    if (!$query) {
        log_message('error', 'Error in fetching new data: ' . $CI->db->error());
        return;
    }

    log_message('info', 'Fetched new data successfully.');

    // Chèn dữ liệu mới vào bảng daily_bigsale_products
    foreach ($query->result_array() as $row) {
        $insert_data = [
            'product_id' => $row['product_id'],
            'slug' => $row['slug'],
            'name' => $row['name'],
            'image' => $row['image'],
            'brand_name' => $row['brand_name'],
            'brand_image' => $row['brand_image'],
            'selling_price' => $row['selling_price'],
            'cost_price' => $row['cost_price'],
            'discount_percentage' => $row['discount_percentage'],
            'category_id' => $row['category_id'],
            'category_name' => $row['category_name'],
            'category_slug' => $row['category_slug'],
            'subcategory_id' => $row['subcategory_id'],
            'subcategory_name' => $row['subcategory_name'],
            'in_stock' => $row['in_stock']
        ];

        $CI->db->insert('daily_bigsale_products', $insert_data);
    }

    log_message('info', 'Update completed successfully!');
}

// Call the function
update_bigsale_products_db($CI);

echo "Update process completed.\n";