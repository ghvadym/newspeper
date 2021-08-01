<?php

add_action('init', 'createPostTypeMagazines');
add_action('init', 'createPostTypeNewspapers');
add_action('init', 'createPostTypeAuthor');

function createPostTypeMagazines()
{
    $labels = [
        'name'          => __('Magazines', 'newspaper'),
        'singular_name' => __('Magazines', 'newspaper'),
        'add_new_item'  => __('Add New Magazine', 'newspaper'),
        'view_item'     => __('View Magazine', 'newspaper'),
        'search_items'  => __('Search Magazine', 'newspaper'),
        'not_found'     => __('No News found', 'newspaper'),
        'menu_name'     => __('Magazines', 'newspaper'),
    ];
    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-text-page',
        'menu_position' => 20,
        'hierarchical'  => true,
        'supports'      => ['title', 'excerpt', 'thumbnail', 'editor'],
    ];

    register_post_type('magazines', $args);
}

function createPostTypeNewspapers()
{
    $labels = [
        'name'          => __('Newspapers', 'newspaper'),
        'singular_name' => __('Newspapers', 'newspaper'),
        'add_new_item'  => __('Add New Newspaper', 'newspaper'),
        'view_item'     => __('View Newspaper', 'newspaper'),
        'search_items'  => __('Search Newspaper', 'newspaper'),
        'not_found'     => __('No News found', 'newspaper'),
        'menu_name'     => __('Newspapers', 'newspaper'),
    ];
    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-format-aside',
        'menu_position' => 20,
        'hierarchical'  => true,
        'supports'      => ['title', 'excerpt', 'thumbnail', 'editor'],
    ];

    register_post_type('newspapers', $args);
}

function createPostTypeAuthor()
{
    $labels = [
        'name'          => __('Authors', 'newspaper'),
        'singular_name' => __('Authors', 'newspaper'),
        'add_new_item'  => __('Add New Author', 'newspaper'),
        'view_item'     => __('View Authors', 'newspaper'),
        'search_items'  => __('Search Authors', 'newspaper'),
        'not_found'     => __('No News found', 'newspaper'),
        'menu_name'     => __('Authors', 'newspaper'),
    ];
    $args = [
        'labels'        => $labels,
        'public'        => true,
        'show_ui'       => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-buddicons-buddypress-logo',
        'menu_position' => 20,
        'hierarchical'  => true,
        'supports'      => ['title', 'thumbnail', 'editor'],
    ];

    register_post_type('authors', $args);
}


add_action('init', 'createTaxonomyCategoriesMagazines');
add_action('init', 'createTaxonomyLabelsMagazines');

function createTaxonomyCategoriesMagazines()
{
    $labels = [
        'singular_name'     => __('Categories', 'newspaper'),
        'search_items'      => __('Search Category', 'newspaper'),
        'all_items'         => __('All Categories', 'newspaper'),
        'parent_item'       => __('Parent Category', 'newspaper'),
        'parent_item_colon' => __('Parent Category:', 'newspaper'),
        'edit_item'         => __('Edit Category', 'newspaper'),
        'update_item'       => __('Update Category', 'newspaper'),
        'add_new_item'      => __('Add New Category', 'newspaper'),
        'new_item_name'     => __('New Category Name', 'newspaper'),
        'menu_name'         => __('Categories', 'newspaper'),
    ];

    $args = [
        'labels'       => $labels,
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ];

    register_taxonomy('categories', 'magazines', $args);
}

function createTaxonomyLabelsMagazines()
{
    $labels = [
        'name'              => __('Labels', 'newspaper'),
        'singular_name'     => __('Labels', 'newspaper'),
        'search_items'      => __('Search Label', 'newspaper'),
        'all_items'         => __('All Labels', 'newspaper'),
        'parent_item'       => __('Parent Label', 'newspaper'),
        'parent_item_colon' => __('Parent Label:', 'newspaper'),
        'edit_item'         => __('Edit Label', 'newspaper'),
        'update_item'       => __('Update Label', 'newspaper'),
        'add_new_item'      => __('Add New Label', 'newspaper'),
        'new_item_name'     => __('New Label Name', 'newspaper'),
        'menu_name'         => __('Labels', 'newspaper'),
    ];

    $args = [
        'labels'       => $labels,
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ];

    register_taxonomy('label', 'magazines', $args);
}

add_action('init', 'createTaxonomyCategoriesNewspapers');
add_action('init', 'createTaxonomyLabelsNewspapers');

function createTaxonomyCategoriesNewspapers()
{
    $labels = [
        'name'              => __('Categories Newspapers', 'newspaper'),
        'singular_name'     => __('Categories Newspapers', 'newspaper'),
        'search_items'      => __('Search Category Newspaper', 'newspaper'),
        'all_items'         => __('All Categories', 'newspaper'),
        'parent_item'       => __('Parent Category', 'newspaper'),
        'parent_item_colon' => __('Parent Category:', 'newspaper'),
        'edit_item'         => __('Edit Category', 'newspaper'),
        'update_item'       => __('Update Category', 'newspaper'),
        'add_new_item'      => __('Add New Category', 'newspaper'),
        'new_item_name'     => __('New Category Name', 'newspaper'),
        'menu_name'         => __('Categories', 'newspaper'),
    ];

    $args = [
        'labels'       => $labels,
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ];

    register_taxonomy('categories-newspapers', 'newspapers', $args);
}

function createTaxonomyLabelsNewspapers()
{
    $labels = [
        'name'              => __('Labels Newspapers', 'newspaper'),
        'singular_name'     => __('Labels Newspapers', 'newspaper'),
        'search_items'      => __('Search Label Newspapers', 'newspaper'),
        'all_items'         => __('All Labels', 'newspaper'),
        'parent_item'       => __('Parent Label', 'newspaper'),
        'parent_item_colon' => __('Parent Label:', 'newspaper'),
        'edit_item'         => __('Edit Label', 'newspaper'),
        'update_item'       => __('Update Label', 'newspaper'),
        'add_new_item'      => __('Add New Label', 'newspaper'),
        'new_item_name'     => __('New Label Name', 'newspaper'),
        'menu_name'         => __('Labels', 'newspaper'),
    ];

    $args = [
        'labels'       => $labels,
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ];

    register_taxonomy('labels-newspapers', 'newspapers', $args);
}