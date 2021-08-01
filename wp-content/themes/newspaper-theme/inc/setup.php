<?php

add_action('wp_enqueue_scripts', 'theme_styles');
function theme_styles()
{
    wp_enqueue_style('style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('app-style', get_template_directory_uri() . '/assets/css/app.css');
    wp_enqueue_style('owl-style', get_template_directory_uri() . '/assets/css/owl.carousel.min.css');
}

add_action('wp_enqueue_scripts', 'theme_scripts');
function theme_scripts()
{
    wp_enqueue_script("jquery");
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/app.js', ['jquery'], time(), true);
    wp_enqueue_script('owl-script', get_template_directory_uri() . '/assets/js/owl.carousel.min.js', ['jquery'], time(), true);
    wp_localize_script('main-script', 'myajax', [
        'ajaxurl'   => admin_url('admin-ajax.php')
    ]);
}

add_filter('upload_mimes', 'upload_allow_types');
function upload_allow_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    $mimes['tiff|tif'] = 'image/tiff';

    return $mimes;
}

add_action('after_setup_theme', function () {

    register_nav_menus([
        'main_header' => 'Main Header',
    ]);

});

add_theme_support('post-thumbnails', ['newspapers', 'magazines', 'authors']);

if (function_exists('acf_add_options_page')) {
    acf_add_options_page([
        'page_title' => 'Options',
        'menu_title' => 'Options',
        'menu_slug'  => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ]);
}

add_action('admin_menu', 'remove_default_post_types');
function remove_default_post_types()
{
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}