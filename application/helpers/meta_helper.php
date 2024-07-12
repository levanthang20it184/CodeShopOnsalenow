<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

function getMetaDetailsByCategorySlug($slug)
{

    $ci = &get_instance();
    $ci->db->select("meta_title,meta_tag,meta_description");
    $ci->db->from('ci_category');
    $ci->db->where('ci_category.slug', $slug);
    $q = $ci->db->get()->row();
    // echo "<pre>"; print_r($q); die;
    return $q;

}

function getMetaDetailsByBrandSlug($slug)
{

    $ci = &get_instance();
    $ci->db->select("meta_title,meta_tag,meta_description");
    $ci->db->from('ci_brand');
    $ci->db->where('ci_brand.slug', $slug);
    $q = $ci->db->get()->row();
    // echo "<pre>"; print_r($q); die;
    return $q;

}

?>