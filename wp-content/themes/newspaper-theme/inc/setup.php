<?php

add_action(  'wp_enqueue_styles', 'theme_styles' );
function theme_styles() {
    wp_enqueue_style( 'style' , get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'app-style' , get_template_directory_uri() . '/assets/css/app.css' );
}

add_action(  'wp_enqueue_scripts', 'theme_scripts' );
function theme_scripts() {
    wp_enqueue_script( 'main-script', get_template_directory_uri() . '/assets/js/app.js', ['jquery'], false, true );
//    wp_localize_script( 'main-script', 'my-ajax', ['ajaxurl' => admin_url('admin-ajax.php')] );
}

if (!function_exists('dd')) {
    function dd()
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}

add_filter('upload_mimes', 'upload_allow_types');
function upload_allow_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';

    return $mimes;
}
