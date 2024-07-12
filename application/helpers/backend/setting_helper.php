<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


function get_general_settings()
{
    $ci = &get_instance();
    $ci->db->where('id', 1);
    $query = $ci->db->get('ci_general_settings');
    return $query->row_array();
}

function general_settings($field = '')
{
    $ci = &get_instance();
    $ci->db->where('id', 1);
    $ci->db->select($field);
    $query = $ci->db->get('ci_general_settings');
    return $query->row()->$field;
}


?>