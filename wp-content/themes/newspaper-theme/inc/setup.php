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
    wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/app.js', ['jquery', 'aos-script'], time(), true);
    wp_enqueue_script('owl-script', get_template_directory_uri() . '/assets/js/libs/owl.carousel.min.js', ['jquery'], time(), true);

    wp_localize_script('main-script', 'myajax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
    ]);
}

add_filter('upload_mimes', 'upload_allow_types');
function upload_allow_types($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';

    return $mimes;
}

add_action('after_setup_theme', function () {

    register_nav_menus([
        'main_header'                  => 'Main Header',
        'footer_pages'                 => 'Footer Pages',
        'footer_labels_magazines'      => 'Footer Labels Magazines',
        'footer_categories_magazines'  => 'Footer Categories Magazines',
        'footer_labels_newspapers'     => 'Footer Labels Newspapers',
        'footer_categories_newspapers' => 'Footer Categories Newspapers',
    ]);

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

});

add_action('admin_menu', 'remove_default_post_types');
function remove_default_post_types()
{
    remove_menu_page('edit.php');
    remove_menu_page('edit-comments.php');
}

add_filter('get_the_archive_title', 'remove_title_from_archive');
function remove_title_from_archive($title)
{
    return preg_replace('~^[^:]+: ~', '', $title);
}

add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );