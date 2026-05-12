<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_shop_option')) {
    function get_shop_option($key, $default = null)
    {
        $value = get_cms_option($key, $default);
        
        // Handle JSON decoding for shop options (e.g. lists of countries)
        if (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $decoded;
            }
        }
        
        return $value;
    }
}

if (!function_exists('update_shop_option')) {
    function update_shop_option($key, $value)
    {
        if (is_array($value) || is_object($value)) {
            $value = json_encode($value);
        }
        
        return update_cms_option($key, $value);
    }
}
