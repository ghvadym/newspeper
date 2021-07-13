<?php

add_action( 'init', 'createPostTypeNews');
function createPostTypeNews() {
    $labels = [
        'name'           => __( 'News', 'newspaper'),
        'singular_name'  => __( 'News', 'newspaper'),
        'add_new_item'   => __( 'Add New News', 'newspaper'),
        'view_item'      => __( 'View News', 'newspaper'),
        'search_items'   => __( 'Search News', 'newspaper'),
        'not_found'      => __( 'No News found', 'newspaper'),
        'menu_name'      => __('News', 'newspaper'),
    ];
    $args = [
        'labels'         => $labels,
        'public'         => true,
        'show_ui'        => true,
        'has_archive'    => true,
        'menu_icon'      => 'dashicons-format-aside',
        'menu_position'  => 20,
        'hierarchical'   => true,
        'supports'       => [ 'title', 'excerpt', 'author', 'thumbnail' ]
    ];

    register_post_type('news', $args);
}

add_action( 'init', 'createTaxonomyCategory');
function createTaxonomyCategory() {
    $labels = [
        'name'              => __( 'Categories', 'newspaper' ),
        'singular_name'     => __( 'Categories', 'newspaper' ),
        'search_items'      => __( 'Search Category', 'newspaper' ),
        'all_items'         => __( 'All Categories', 'newspaper' ),
        'parent_item'       => __( 'Parent Category', 'newspaper' ),
        'parent_item_colon' => __( 'Parent Category:', 'newspaper' ),
        'edit_item'         => __( 'Edit Category', 'newspaper' ),
        'update_item'       => __( 'Update Category', 'newspaper' ),
        'add_new_item'      => __( 'Add New Category', 'newspaper' ),
        'new_item_name'     => __( 'New Category Name', 'newspaper' ),
        'menu_name'         => __( 'Categories', 'newspaper' ),
    ];

    $args = [
        'labels'       => $labels,
        'description'  => '',
        'public'       => true,
        'hierarchical' => true,
        'has_archive'  => true,
    ];

    register_taxonomy('categories', 'news', $args);
}