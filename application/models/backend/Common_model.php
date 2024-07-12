<?php

class Common_model extends CI_Model
{
    public function get_data_count($table, $where = array())
    {
        $this->db->where($where);
        return $this->db->count_all_results($table);
    }

    // public function get_data($table, $where = array())
    // {
    //     if (!empty($where)) {
    //         $this->db->where($where);
    //         echo 'table';
    //     } else{
    //         echo 'not found';
    //     }

    //     // $this->db->limit('100000');
    //     return $this->db->get($table)->result_array();
    // }
    public function get_data($table, $limit=null , $offset=null) {
        $this->db->limit($limit, $offset);
        $query = $this->db->get($table);
        return $query->result();
    }

    public function get_OSNCatAndSubCat($table, $where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->order_by('name', 'asc');

        return $this->db->get($table)->result_array();
    }

    public function getCronReports($table, $where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->order_by('id', 'desc');
        return $this->db->get($table)->result_array();
    }

    public function get_total()
    {
        $this->db->where('ci_products.status', '1');
        $query = $this->db->get('ci_products')->num_rows();
        // echo $this->db->last_query(); die;
        return $query;
        $this->db->close();
    }

    public function getRows($params = array())
    {
        $this->db->select('*');
        $this->db->from('ci_products');
        //$this->db->where('status','1');
        if (array_key_exists("where", $params)) {
            foreach ($params['where'] as $key => $val) {
                $this->db->where($key, $val);
            }
        }

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
                $this->db->order_by('id', 'desc');
                if (array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit'], $params['start']);
                } elseif (!array_key_exists("start", $params) && array_key_exists("limit", $params)) {
                    $this->db->limit($params['limit']);
                }

                $query = $this->db->get();
                $result = ($query->num_rows() > 0) ? $query->result_array() : false;
            }
        }

        // Return fetched data
        return $result;
    }

    public function get_product_data()
    {
        $limit = 1000;
        $offset = 0;
        $this->db->select('ci_products.id, ci_products.slug, ci_products.name,ci_products.image,merchant_products.*, ci_category.slug as categorySlug');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->join('ci_category', 'ci_category.id = ci_products.category_id', 'right');
        //$this->db->where('ci_products.status','1');
        $this->db->group_by('merchant_products.name_wp');
        $this->db->order_by("ci_products.id ", "DESC");
        if ($limit !== null)
            $this->db->limit($limit);
        $this->db->offset($offset);
        $query = $this->db->get()->result_array();
        // $query = $this->db->get()->result();
        return $query;
    }

    public function getProductListPage($start, $recordPerPage, $orderBy, $whereCondition, $isTotal = false)
    {
        $exchangeRate = get_exchange_rate();

        $this->db->select("ci_products.*,merchant_products.selling_price, merchant_products.cost_price, merchant_products.updated_at AS merchant_updated_at, merchant_products.stock,
        CASE
        WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
        WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
        ELSE ROUND(merchant_products.selling_price, 2)
        END AS product_price");
        $this->db->select("merchant_products.selling_price / merchant_products.cost_price as discount_percent", false);

        $this->db->from('ci_products');

        $this->db->join('merchant_products', 'merchant_products.name_wp=ci_products.name_wp', 'Left');

        if ($whereCondition)
            $this->db->where($whereCondition);
        $this->db->where('merchant_products.selling_price <> 0');
        $this->db->where('merchant_products.cost_price <> 0');
        $this->db->where('merchant_products.selling_price IS NOT NULL');
        $this->db->where('merchant_products.cost_price IS NOT NULL');

        $this->db->group_by('ci_products.id');
        $this->db->order_by($orderBy);

        if ($isTotal) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($recordPerPage, $start);
            return $this->db->get()->result();
        }
    }

    public function get_slug_data($table, $column = '*', $like, $name = 'data')
    {
        $this->db->select($column);
        $this->db->from($table);
        if ($name = '') {
            $this->db->where($where);
        }

        foreach ($like as $key => $value) {
            $this->db->like("$key", $value);
        }
        return $this->db->get()->result_array();
    }

    public function get_single_data($table, $where = array())
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->get($table)->row_array();
    }

    public function getSlugList()
    {
        $this->db->select('slug');
        $this->db->from('ci_brand');

        $result = $this->db->get()->result_array();

        $slugs = [];
        foreach ($result as $row) {
            $slug = $row['slug'] == null ? '' : $row['slug'];
            if (!in_array($slug, $slugs)) {
                $slugs[] = $slug;
            }
        }

        return $slugs;
    }

    public function getAliasList()
    {
        $this->db->select('alias');
        $this->db->from('ci_brand');

        $result = $this->db->get()->result_array();

        $aliasList = [];
        foreach ($result as $row) {
            $alias = $row['alias'] == null ? '' : $row['alias'];
            if (!in_array($alias, $aliasList)) {
                $aliasList[] = $alias;
            }
        }

        return $aliasList;
    }

    public function get_metatag_data($table, $where = array())
    {

        if (!empty($where)) {
            $this->db->where($where);
        }

        return $this->db->get($table)->row_array();
    }

    public function update_data($table, $where, $data)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        return true;
    }

    public function update_product_meta_tag($table, $where, $data)
    {
        $this->db->where($where);
        $this->db->update($table, $data);
        // echo $this->db->last_query(); die;
        return true;
    }

    public function add_data($table, $data)
    {
        $this->db->insert($table, $data);
        return true;
    }

    public function delete_row($table, $where)
    {
        $this->db->where($where);
        $this->db->delete($table);
        return true;
    }

    public function getBrandData()
    {
        $this->db->select('ci_brand.*, COALESCE(product_counts.row_count, 0) as product_count');
        $this->db->from('ci_brand');
        $this->db->join('(SELECT brand_id, COUNT(*) as row_count FROM ci_products GROUP BY brand_id) AS product_counts', 'ci_brand.id = product_counts.brand_id', 'left');
        // $this->db->having('product_count >', 0);
        $this->db->order_by('product_count', 'desc');
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function getNoImageBrands()
    {
        $this->db->from('ci_brand');

        $this->db->where("image", '');

        return $this->db->get()->result_array();
    }

    public function getNoImageBrandCnt()
    {
        $this->db->where("image", '');
        return $this->db->count_all_results("ci_brand");
    }

    public function getProductCount($brandId, $categoryId, $subCategoryId)
    {
        $this->db->where("brand_id", $brandId);
        $this->db->where("category_id", $categoryId);
        $this->db->where("subCategory_id", $subCategoryId);
        return $this->db->count_all_results("ci_products");
    }

    public function getMerchantList()
    {
        $this->db->select("id, merchant_name");
        $this->db->from('ci_merchant');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getCronJobList()
    {
        $this->db->select("cron_jobs.id, cron_jobs.feed_url, cron_jobs.start_upload_at, cron_jobs.last_uploaded_at, cron_jobs.last_uploaded_size, cron_jobs.currency, cron_jobs.status, ci_merchant.merchant_name");
        $this->db->from('cron_jobs');
        $this->db->join('ci_merchant', 'cron_jobs.merchant_id = ci_merchant.id', 'Left');
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function getCategoryList($category, $subCategory)
    {
        $this->db->select("*");
        $this->db->from('category_map');
        $this->db->where('category', $category);
        $this->db->where('subCategory', $subCategory);

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function updateBrandImageLink($id, $imageLink)
    {
        $data = array(
            'image' => $imageLink,
        );

        $this->db->where('id', $id);

        $this->db->update("ci_brand", $data);
    }

    public function insertOrUpdate($categoryMap)
    {
        $isRemove = false;
        if (intval($categoryMap[2]) <= 0 || intval($categoryMap[3]) <= 0) {
            $isRemove = true;
        }

        if ($isRemove) {
            $this->db->delete('category_map', array('category' => $categoryMap[0], 'subCategory' => $categoryMap[1]));
        } else {
            $data = array(
                'osn_category_id' => intval($categoryMap[2]),
                'osn_subCategory_id' => intval($categoryMap[3]),
                'category' => $categoryMap[0],
                'subCategory' => $categoryMap[1],
            );

            $this->db->where('category', $categoryMap[0]);
            $this->db->where('subCategory', $categoryMap[1]);

            $already = $this->db->get("category_map")->result();

            if (!empty($already)) {
                $this->db->where('category', $categoryMap[0]);
                $this->db->where('subCategory', $categoryMap[1]);
                $this->db->update("category_map", $data);
                return;
            }

            $this->db->insert("category_map", $data);
        }
    }

 
    public function addOrUpdateProduct($products)
    {
     
        ini_set('memory_limit', '10028M');
        try {
        $grouped_products = array();

        foreach ($products as $product) {
            $key = $product['name'] . '|' . $product['brand_id'];
            // log_message("debug", "key : $key");
            if (!isset($grouped_products[$key])) {
                $grouped_products[$key] = $product;
                $grouped_products[$key]['size'] = array($product['size']);
                $grouped_products[$key]['color'] = array($product['color']);
                $grouped_products[$key]['option'] = array($product['option']);
            } else {
                $grouped_products[$key]['size'][] = $product['size'];
                $grouped_products[$key]['color'][] = $product['color'];
                $grouped_products[$key]['option'][] = $product['option'];
            }
        }


            $batch_size = 1000;
            $grouped_product_batches = array_chunk($grouped_products, $batch_size, true);
    
            foreach ($grouped_product_batches as $batch) {
            $insert_values = array();
            $params = array();

                foreach ($batch as $product) {
                    // Remove duplicates from the size, color, and option arrays
                    $product['size'] = array_keys(array_flip($product['size']));
                    $product['color'] = array_keys(array_flip($product['color']));
                    $product['option'] = array_keys(array_flip($product['option']));
        
                    // sort($product['size'], SORT_NATURAL | SORT_FLAG_CASE);
        
                    $order = ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl'];
                    $special_order = ['x small', 'small', 'medium', 'large', 'x large'];
                    
                    // Create a size map for faster lookup
                    $size_map = array_flip($order);
                    $special_size_map = array_flip($special_order);

                    // $ci_product_query = "SELECT size FROM ci_products WHERE name = ? LIMIT 1";
                    // $product_name = preg_replace('/[^\x00-\x7F]/u', '', $product['name']); // Remove non-ASCII characters

                    // $ci_product = $this->db->query($ci_product_query, array($product_name))->row();                
                    // if ($ci_product) {
                    //     $ci_product_size = explode(' | ', $ci_product->size);
                    //     $product['size'] = array_merge($product['size'], $ci_product_size);
                    //     $product['size'] = array_unique($product['size']);
                    // }
                    usort($product['size'], function ($a, $b) use ($size_map, $special_size_map) {
                        if (preg_match('/^(\d+)\s+(.*)$/', $a, $matches_a) && preg_match('/^(\d+)\s+(.*)$/', $b, $matches_b)) {
                            if ($matches_a[1] == $matches_b[1]) {
                                return strcmp($matches_a[2], $matches_b[2]);
                            }
                            return $matches_a[1] - $matches_b[1];
                        }
                    
                        if (is_numeric($a) && is_numeric($b)) {
                            return $a - $b;
                        }
                    
                        if (is_numeric($a)) {
                            return -1;
                        }
                    
                        if (is_numeric($b)) {
                            return 1;
                        }
                    
                        $a_index = $size_map[strtolower($a)] ?? null;
                        $b_index = $size_map[strtolower($b)] ?? null;
                    
                        $a_special_index = $special_size_map[strtolower($a)] ?? null;
                        $b_special_index = $special_size_map[strtolower($b)] ?? null;
                    
                        if ($a_index !== null && $b_index !== null) {
                            return $a_index - $b_index;
                        }
                    
                        if ($a_special_index !== null && $b_special_index !== null) {
                            return $a_special_index - $b_special_index;
                        }
                    
                        if ($a_index !== null) {
                            return -1;
                        }
                    
                        if ($b_index !== null) {
                            return 1;
                        }
                    
                        if ($a_special_index !== null) {
                            return -1;
                        }
                    
                        if ($b_special_index !== null) {
                            return 1;
                        }
                    
                        return $a > $b;
                    });

                    // Convert the arrays back to strings
                    $product['size'] = implode(' | ', $product['size']);
                    $product['color'] = implode(' | ', $product['color']);
                    $product['option'] = implode(' | ', $product['option']);
        
                    $insert_values[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                    foreach ($product as $value) {
                        $params[] = $value;
                    }
                }      


               
            
    
         }

        $insert_query = 'INSERT INTO ci_products (brand_id, category_id, subCategory_id, name, description, image, slug, status, name_wp, cron_job_id, `size`, `color`, `option`)
    VALUES ' . implode(',', $insert_values);

        $update_query = " ON DUPLICATE KEY UPDATE brand_id = VALUES(brand_id), category_id = VALUES(category_id), subCategory_id = VALUES(subCategory_id), name = VALUES(name), description = VALUES(description), image = VALUES(image), status = VALUES(status), cron_job_id = VALUES(cron_job_id), `size` = VALUES(`size`), `color` = VALUES(`color`), `option` = VALUES(`option`), updated_at = NOW()";
        $query = $insert_query . $update_query;
        $this->db->query($query, $params);

        $productCount = count($grouped_products);

        $updated_rows = $this->db->affected_rows() - $productCount;
        $inserted_rows = $productCount - $updated_rows;

        return [$inserted_rows, $updated_rows];
    
    }catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());

        // You can also return the error message to the caller if needed
        return ['error' => $e->getMessage()];
    }
}

    public function getMaxId()
    {
        $this->db->select_max('id');
        $this->db->from('cron_jobs');
        $query = $this->db->get();
        return intval(($query->result())[0]->id);
    }

    public function getCronJob($id)
    {
        $this->db->select("*");
        $this->db->from('cron_jobs');
        $this->db->where('id', $id);

        $result = ($this->db->get()->result_array())[0];
        return $result;
    }

    public function getAllCronJob()
    {
        $this->db->select("*");
        $this->db->from('cron_jobs');
        $this->db->where('status', 1);
        $this->db->where('start_upload_at <=', date('Y-m-d'));

        $result = ($this->db->get()->result_array());
        return $result;
    }

    public function getBrandId($brandName)
    {
        $brandName = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $brandName);
    
        $this->db->select("id");
        $this->db->from('ci_brand');
        $this->db->where('brand_name', $brandName);
    
        $result = $this->db->get()->result_array();
        return intval($result[0]["id"]);
        // if (!empty($result)) {
        //     return intval($result[0]["id"]);
        // } else {
        //     return null; 
        // }
    }
    

    public function getDepAndCat($categoryName, $subCategoryName)
    {
        $this->db->select("osn_category_id, osn_subCategory_id");
        $this->db->from('category_map');
        $this->db->where('category', $categoryName);
        $this->db->where('subCategory', $subCategoryName);
        $result = ($this->db->get()->result_array())[0];
        // $query = $this->db->get();
        
        // if ($query->num_rows() > 0) {
        //     $result = $query->row_array(); 
        // } else {
        //     $result = NULL;
        // }
        
        return $result;
    }
    

    public function isApproved($brandId)
    {
        if ($brandId == 0) {
            return 0;
        }

        $this->db->select("status");
        $this->db->from('ci_brand');
        $this->db->where('id', $brandId);

        $result = ($this->db->get()->result_array())[0]["status"];
        return $result;
    }

    public function getSluganize($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        // $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);       
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }

    public function getSlug($text, string $divider = '-')
    {
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

        // transliterate
        $text = @iconv('utf-8', 'us-ascii//TRANSLIT', $text);       

        // $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $divider);

        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        // while ($this->isDuplicate($text)) {
        //     $text .= '_1';
        // }

        return $text;
    }

    public function isDuplicate($slug)
    {
        $this->db->where('slug', $slug);
        $this->db->from('ci_products');

        $result = $this->db->get()->result_array();
        return count($result) > 0;
    }

    public function removeProducts($cron_job_id)
    {
        $this->db->delete('ci_products', array('cron_job_id' => $cron_job_id));
        $this->db->delete('merchant_products', array('cron_job_id' => $cron_job_id));
    }

    public function addOrUpdateMerchant($data_merchant, $chunkSize = 1000)
    {
        // Initialize counters for inserted and updated rows
        $totalInserted = 0;
        $totalUpdated = 0;
    
        // Process data in chunks
        foreach (array_chunk($data_merchant, $chunkSize) as $chunk) {
            // Reset query components for each chunk
            $insert_values = [];
            $update_values = [];
            $params = [];
    
            foreach ($chunk as $data) {
                // Prepare the insert values
                $insert_values[] = '(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    
                // Prepare the update values
                $update_value = '';
                foreach ($data as $field => $value) {
                    $update_value .= "`$field` = VALUES(`$field`), ";
                    $params[] = $value;
                }
                $update_value .= "updated_at = NOW()";
                $update_values[] = $update_value;
            }
    
            // Construct the SQL query for the current chunk
            $query = "INSERT INTO merchant_products (merchant_id, name_wp, selling_price, cost_price, currency, merchant_store_url, stock, `options`, updated_at, cron_job_id, `size`, `color`, `option`) 
            VALUES " . implode(",", $insert_values) .
                    " ON DUPLICATE KEY UPDATE " . implode(",", $update_values);
    
            // Execute the query for the current chunk
            $this->db->query($query, $params);
    
            // Calculate the number of inserted and updated rows for the current chunk
            $productCnt = count($chunk);
            $updated_rows = $this->db->affected_rows() - $productCnt;
            $inserted_rows = $productCnt - $updated_rows;
    
            // Accumulate totals
            $totalInserted += $inserted_rows;
            $totalUpdated += $updated_rows;
        }
    
        return [$totalInserted, $totalUpdated];
    }
    
    public function addNewBrandIfUnexist($brandName)
    {
        $brandName = preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $brandName);

        $this->db->where('brand_name', $brandName);
        $this->db->from("ci_brand");

        $result = $this->db->get()->result_array();

        if (count($result) == 0) {
            $data = array(
                'brand_name' => $brandName,
                'alias' => $brandName,
                'slug' => $this->getSlug($brandName),
                'status' => 0,
            );

            $this->db->insert("ci_brand", $data);
        }
    }

    public function getMerchantName($merchantId)
    {

        $this->db->select("merchant_name");
        $this->db->from('ci_merchant');
        $this->db->where('id', $merchantId);

        $result = ($this->db->get()->result_array())[0]["merchant_name"];
        return $result;
    }

    public function removeSubCategory($subCagetoryId)
    {
        $this->db->delete('ci_subcategory', array('id' => $subCagetoryId));
    }

    public function renameSubCategory($subCagetoryId, $newName)
    {
        $data = array(
            'name' => $newName,
        );

        $this->db->where('id', $subCagetoryId);
        $this->db->update('ci_subcategory', $data);
    }

    public function addCategory($newName)
    {
        $data = array(
            'name' => $newName,
            'created_at' => date("Y-m-d h:m:s"),
            'updated_at' => date("Y-m-d h:m:s"),
            'status' => 1,
            'slug' => $this->getSlug($newName),
        );

        $this->db->insert('ci_category', $data);
        return $this->db->insert_id();
    }

    public function addSubCategory($cagetoryId, $newName)
    {
        $data = array(
            'name' => $newName,
            'created_at' => date("Y-m-d h:m:s"),
            'updated_at' => date("Y-m-d h:m:s"),
            'status' => 1,
            'slug' => $this->getSlug($newName),
            'category_id' => $cagetoryId,
        );

        $this->db->insert('ci_subcategory', $data);
        return $this->db->insert_id();
    }

    public function removeCronJob($id)
    {
        $this->db->delete('cron_jobs', array('id' => $id));

        // remove from ci_products
        $this->db->where('cron_job_id', $id);
        $this->db->delete('ci_products');

        // remove from merchant_products
        $this->db->where('cron_job_id', $id);
        $this->db->delete('merchant_products');
    }

    // for duplication logic
    public function extract_params_from_product_name($product_name)
    {
        $params = array();
        while ($param = $this->get_next_param($product_name)) {
            if ($param['name'] != $product_name) {
                $product_name = $param['name'];
                foreach ($param['params'] as $p => $v) {
                    $params[$p] = $v;
                }
            } else {
                break;
            }
        }
        return array($product_name, $params);
    }

    public function get_next_param($product_name)
    {
        // $product_name = iconv('UTF-8', 'ISO-8859-1//IGNORE', $product_name); // Convert encoding to Latin1
        // $product_name = str_replace("∅", "", $product_name); // Replace "∅" with empty string
        // $name = $product_name;
        list($product_name, $weight) = $this->parse_weight($product_name);
        list($product_name, $length) = $this->parse_length($product_name);

        list($product_name, $volume) = $this->parse_volume($product_name);

        list($product_name, $colour) = $this->parse_colour($product_name);
        list($product_name, $size) = $this->parse_size($product_name);
        list($product_name, $value) = $this->parse_value($product_name);
        return array(
            // 'name' => $name,
            'name' => $product_name,

            'params' => array(
                'weight' => $weight,
                'length' => $length,
                'volume' => $volume,
                'colour' => $colour,
                'value' => $value,
                'size' => $size,
            )
        );
    }


    public function parse_weight($name)
    {
        // if ($name === null) {
        //     return array('', '');
        // }
    
        $weight = '';
        $weight_params = array('g', 'gm', 'mg', 'kg');
        $weight_params = join('|', $weight_params);
        $trim_chars = "[(])- \t\n\r\0\x0B";
        $matches = '';
        if (preg_match('~[0-9.]+\s?\(?\s?(' . $weight_params . ')\)?$~i', $name, $matches)) {
            $weight = trim($matches[0], $trim_chars);
            $name = trim(str_replace($matches[0], '', $name));
        }
        return array($name, $weight);
    }

    public function parse_length($name)
    {
        $length = '';
        $length_params = array('mm', 'cm', 'm');
        $length_params = join('|', $length_params);
        $trim_chars = "[(])- \t\n\r\0\x0B";
        $matches = '';
        if (preg_match('~[0-9.]+\s?\(?\s?(' . $length_params . ')\)?$~i', $name, $matches)) {
            $length = trim($matches[0], $trim_chars);
            $name = trim(str_replace($matches[0], '', $name));
        }
        return array($name, $length);
    }

    public function parse_volume($name)
    {
        $volume = '';
        $volume_params = array('ml', 'l', 'fl.oz', 'oz', 'litre', 'GB', 'gb');
        $volume_params = join('|', $volume_params);
        $trim_chars = "/[(])- \t\n\r\0\x0B";
        $matches = '';
        if (preg_match('~([0-9.x]+)\s?\(?\s?(' . $volume_params . ')\)?\.?$~i', $name, $matches)) {
            $volume = trim($matches[1] . ' ' . $matches[2], $trim_chars);
            $name = trim(str_replace($matches[0], '', $name));
            $name = trim($name, $trim_chars);
        }
        return array($name, $volume);
    }

    public function parse_colour($name)
    {
        $colour = '';
        $colours_params = array('GREY', 'BLACK', 'BROWN', 'BLONDE', 'RED', 'WHITE', 'YELLOW', 'BLUE', 'GREEN', 'PINK', 'PEACH', 'ROSE', 'PURPLE', 'CLEAR', 'MANGO', 'PLUM', 'QUINCE', 'GUAVA', 'grey', 'black', 'brown', 'blonde', 'red', 'white', 'yellow', 'blue', 'green', 'pink', 'peach', 'rose', 'purple', 'clear', 'mango', 'plum', 'quince', 'guava', 'Grey', 'Black', 'Brown', 'Blonde', 'Red', 'White', 'Yellow', 'Blue', 'Green', 'Pink', 'Peach', 'Rose', 'Purple', 'Clear', 'Mango', 'Plum', 'Quince', 'Guava', 'Cyan', 'cyan');
        $colours_params = join('|', $colours_params);
        $trim_chars = "[(])- \t\n\r\0\x0B";
        $matches = '';
        if (preg_match('~\s?\(?\s?\b(' . $colours_params . ')\b\s?\)?$~i', $name, $matches)) {
            $colour = trim($matches[0], $trim_chars);
            $name = trim(str_replace($matches[0], '', $name));
        }
        return array($name, $colour);
    }

    public function parse_value($name)
    {
        $value = '';
        $value_params = array('tablets', 'vegan capsules', 'capsules', 'products', 'pack', 'washes');
        $value_params = join('|', $value_params);
        $trim_chars = "[(])- \t\n\r\0\x0B";
        $matches = '';

        if (preg_match('~[0-9.]+\s?\(?\s?(' . $value_params . ')\s?\)?$~i', $name, $matches)) {
            $value = trim($matches[0], $trim_chars);
            $name = trim(str_replace($matches[0], '', $name));
        }
        return array($name, $value);
    }

    public function parse_size($product_name)
    {
        return array($product_name, '');
    }

    public function getOption($options)
    {
        // Loop through each key-value pair in the array
        foreach ($options as $key => $value) {
            // If the value is not an empty string, return it
            if (!empty($value)) {
                return $value;
            }
        }
        // If all values are empty strings, return null or a default value of your choice
        return null;
    }

    public function deleteOutStockProducts()
    {
        $this->db->delete('ci_products', ['updated_at <' => date('Y-m-d H:i:s', strtotime('-30 days'))]);
        $this->db->delete('merchant_products', ['updated_at <' => date('Y-m-d H:i:s', strtotime('-30 days'))]);
    }

    public function getTopCategoryList()
    {
        $this->db->select("category_id");
        $this->db->from('product_visit');
        $this->db->group_by('category_id');
        $this->db->order_by('visit_count', 'DESC');
        $this->db->limit(10);
        $result = $this->db->get()->result();

        $category_ids = array();
        foreach ($result as $row) {
            $category_ids[] = $row->category_id;
        }

        if (count($result) < 10) {
            // Retrieve additional random categories to fill out the list

            $this->db->select('*');
            $this->db->from('ci_category');

            if (count($category_ids) > 0) {
                $this->db->where_not_in('id', $category_ids);
            }

            $this->db->where('status', '1');
            $random_result = $this->db->get()->result();

            foreach ($random_result as $row) {
                $category_ids[] = $row->id;
            }
        }

        return $category_ids;
    }

    public function resetIsTopOfCategory()
    {
        $data = array(
            'is_top' => 0,
        );

        $this->db->update('ci_category', $data);
    }

    public function setIsTopOfCategory($idList)
    {
        $this->db->trans_start(); // Start transaction

        $index = 1; // Initialize index to 0

        foreach ($idList as $id) {
            $this->db->set('is_top', $index); // Set the index value
            $this->db->where('id', $id); // Add the condition for updating specific rows
            $this->db->update('ci_category'); // Perform the update query

            $index++; // Increment the index value for the next row
        }

        $this->db->trans_complete(); // End transaction
    }

    // top 50 product list calculation
    public function getTopProductList()
    {
        $this->db->select("product_id");
        $this->db->from('product_visit');
        $this->db->group_by('product_id');
        $this->db->order_by('visit_count', 'DESC');
        $this->db->limit(100);
        $result = $this->db->get()->result();

        $product_ids = array();
        foreach ($result as $row) {
            $product_ids[] = $row->product_id;
        }

        return $product_ids;
    }

    public function resetIsTopOfProduct()
    {
        $data = array(
            'a_top' => 0
        );

        $this->db->update('ci_products', $data);
    }

    public function setIsTopOfProduct($idList)
    {
        $this->db->trans_start(); // Start transaction

        $index = 1; // Initialize index to 0

        foreach ($idList as $id) {
            $this->db->set('a_top', $index); // Set the index value
            $this->db->where('id', $id); // Add the condition for updating specific rows
            $this->db->update('ci_products'); // Perform the update query

            $index++; // Increment the index value for the next row
        }

        $this->db->trans_complete(); // End transaction
    }

    // low price calculation
    public function resetLowPriceOfCategory()
    {
        // Get exchange rates
        $exchangeRate = get_exchange_rate();

        // Update category low_price with minimum value
        $this->db->query("
            UPDATE ci_category
            SET low_price = (
                SELECT MIN(
                    CASE
                    WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                    WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                    ELSE ROUND(merchant_products.selling_price, 2)
                    END
                )
                FROM ci_products
                JOIN merchant_products ON merchant_products.name_wp = ci_products.name_wp
                WHERE ci_products.status = '1'
                AND selling_price > 0.01
                AND ci_products.category_id = ci_category.id
            )
        ");
    }

    public function resetLowPriceOfBrand()
    {
        // Get exchange rates
        $exchangeRate = get_exchange_rate();

        // Update brand low_price with minimum value
        $this->db->query("
            UPDATE ci_brand
            SET low_price = (
                SELECT MIN(
                    CASE
                    WHEN merchant_products.currency='£' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[0] . ", 2)
                    WHEN merchant_products.currency='$' THEN ROUND(merchant_products.selling_price * " . $exchangeRate[1] . ", 2)
                    ELSE ROUND(merchant_products.selling_price, 2)
                    END
                )
                FROM ci_products
                JOIN merchant_products ON merchant_products.name_wp = ci_products.name_wp
                WHERE ci_products.status = '1'
                AND selling_price > 0.01
                AND ci_products.brand_id = ci_brand.id
            )
        ");
    }

    public function setATopAs0MTop()
    {
        $data = array(
            'a_top' => 0
        );

        $this->db->where('m_top', '1');
        $this->db->update('ci_products', $data);
    }

    public function addPriceHistory()
    {
        set_time_limit(900);
        $start_time = microtime(true);
        
        $today_history_count = $this->get_data_count('price_history', ['history_date' => date('Y-m-d')]);
        if ($today_history_count > 0) {
            log_message('info', 'Nothing to update');
            return 0;
        }
    
        $limit = 200;
        $offset = 0;
        $count = 0;
        
        while (true) {
            $products = $this->get_data('merchant_products', $limit, $offset);   
            if (empty($products)) {
                break;
            }           
            $data = [];
            foreach ($products as $product) {
                $data[] = [
                    'product_id' => $product->id,
                    'history_date' => date('Y-m-d'),
                    'selling_price' => $product->selling_price,
                    'cost_price' => $product->cost_price
                ];
            }
            
            $this->db->trans_start();
            $this->db->insert_batch('price_history', $data);
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                log_message('error', 'Failed to insert price history batch');
                return 0;
            }
    
            $offset += $limit;
            $count += count($data);
        }
        
        $end_time = microtime(true);
        $history_execute_time = $end_time - $start_time;
        
        log_message('info', 'Price history is updated in ' . $history_execute_time . ' seconds');
        return $count;
    }
    
    

    public function addNewProduct($merchant_product, $ci_product)
    {
        $this->db->insert('merchant_products', $merchant_product);
        $this->db->insert('ci_products', $ci_product);
    }

    public function getProductByWPName($wp_name)
    {
        $this->db->select('*');
        $this->db->from('ci_products');
        $this->db->join('merchant_products', 'ci_products.name_wp = merchant_products.name_wp', 'left');
        $this->db->where('ci_products.name_wp', $wp_name);
        $res = $this->db->get()->result();
        return $res;
    }

    public function updateProduct($merchant_product, $ci_product, $wp_name)
    {
        $this->db->where('name_wp', $wp_name);
        $this->db->update('ci_products', $ci_product);
        $this->db->where('name_wp', $wp_name);
        $this->db->update('merchant_products', $merchant_product);
    }

    public function update_stock($id, $stock)
    {
        $sql = "UPDATE merchant_products
                LEFT JOIN ci_products ON ci_products.name_wp = merchant_products.name_wp
                SET merchant_products.stock = $stock
                WHERE ci_products.id = $id";

        $this->db->query($sql);
        return true;
    }
}
