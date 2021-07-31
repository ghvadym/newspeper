<?php

add_action('wp_ajax_archive_pagination', 'archive_pagination');
add_action('wp_ajax_nopriv_archive_pagination', 'archive_pagination');

add_action('wp_ajax_archive_filter', 'archive_filter');
add_action('wp_ajax_nopriv_archive_filter', 'archive_filter');

function archive_pagination()
{
    $postsPerPage = get_option('posts_per_page');
    $page = $_POST['page'] ?? '';
    $offset = $page * $postsPerPage - $postsPerPage;
    $args = [
        'post_status'    => 'publish',
        'posts_per_page' => $postsPerPage,
        'post_type'      => 'news',
        'offset'         => $offset,
        'tax_query'      => [
            [
                'taxonomy' => 'label',
                'operator' => 'EXISTS',
            ],
            [
                'taxonomy' => 'categories',
                'operator' => 'EXISTS',
            ]
        ],
    ];

    queryAjax($args);
}

function archive_filter()
{
    $termsString = $_POST['filter_data'] ?? '';
    $termsArray = explode(',', $termsString);
    $taxQuery = [];

    $args = [
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'post_type'      => 'news',
        'tax_query'      => [
            [
                'taxonomy' => 'label',
                'operator' => 'EXISTS',
            ],
            [
                'taxonomy' => 'categories',
                'operator' => 'EXISTS',
            ]
        ],
    ];

    foreach ($termsArray as $term) {
        $tax = get_term($term);
        $taxQuery[] = [
            'taxonomy' => $tax->taxonomy,
            'field'    => 'id',
            'terms'    => $term,
        ];
    }

    if (!empty($termsString)) {
        $args['tax_query'] = $taxQuery;
    }

    queryAjax($args);
}

function queryAjax($args)
{
    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part_var('templates/components/archive-posts', ['post' => $query->post]);
        endwhile;
    else:
        echo 'no matches';
    endif;
    wp_reset_postdata();

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json(['result' => $html]);
}


