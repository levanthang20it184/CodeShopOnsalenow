<?php

class Category_model extends CI_Model
{


    public function getCategort()
    {
        $this->db->select('ci_category.*');
        $this->db->from('ci_category');
        return $this->db->get()->result_array();
    }


    public function getSubCategort($categoryId)
    {
        $this->db->select('*');
        $this->db->from('ci_subcategory');
        $this->db->where('category_id', $categoryId);
        return $this->db->get()->result_array();
    }

    public function getProductTagsList()
    {

        $this->db->select('ci_category.*');
        $this->db->from('ci_category');
        return $this->db->get()->result_array();

    }

    public function getMerchantCategoryId()
    {
        $categoryName = "Voucher Codes & Sales Events";
        $this->db->select('id');
        $this->db->from('ci_category');
        $this->db->where('name', $categoryName);
        $result = $this->db->get()->result_array();
        if (isset($result[0]['id'])) {
            return $result[0]['id'];
        } else {
            $this->db->insert('ci_category', [
                'name' => $categoryName,
                'slug' => 'top-level-category',
                'meta_description' => '',
            ]);
            $this->db->select('id');
            $this->db->from('ci_category');
            $this->db->where('name', $categoryName);
            $result = $this->db->get()->result_array();
            return $result[0]['id'];
        }
    }
}


?>
