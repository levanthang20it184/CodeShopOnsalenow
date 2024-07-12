<?php
// File: application/helpers/auth_helper.php

if (!function_exists('generate_random_code')) {
    function generate_random_code($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_code = '';
        for ($i = 0; $i < $length; $i++) {
            $random_code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $random_code;
    }
}
