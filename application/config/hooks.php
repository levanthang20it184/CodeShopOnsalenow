<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

$hook['display_override'][] = array(
    'class' => '',
    'function' => 'log_queries',
    'filename' => 'log_queries.php',
    'filepath' => 'hooks'
);

//hooks/log_queries.php

function log_queries() {
$CI =& get_instance();
$times = $CI->db->query_times;
foreach ($CI->db->queries as $key=>$query) {
    log_message('debug', "Query: ".$query." | ".$times[$key]);
}
}
