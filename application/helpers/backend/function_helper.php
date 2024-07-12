<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


if (!function_exists('get_sidebar_menu')) {

    function get_sidebar_menu()

    {

        $ci = &get_instance();

        $ci->db->select('*');

        $ci->db->where(['status' => '1']);

        $ci->db->order_by('sort_order', 'asc');

        return $ci->db->get('module')->result_array();

    }

}

if (!function_exists('get_sidebar_sub_menu')) {

    function get_sidebar_sub_menu($parent_id)

    {

        $ci =& get_instance();

        $ci->db->select('*');

        $ci->db->where('parent', $parent_id);

        $ci->db->order_by('sort_order', 'asc');
        if (isset($_SESSION['is_change_password'])  && $_SESSION['is_change_password'] == 1) {
            return $ci->db->get('sub_module')->result_array();
        }
        return $ci->db->where('name !=', 'change_password')->get('sub_module')->result_array();

    }

}

function trans($string)
{
    $ci =& get_instance();

    return ucfirst(str_replace('_', ' ', $string));

}


?>