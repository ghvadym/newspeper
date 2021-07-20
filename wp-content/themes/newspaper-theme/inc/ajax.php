<?php

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
    ];

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            get_template_part_var('templates/components/archive-posts', ['post' => $query->post]);
        endwhile;
    endif;
    wp_reset_postdata();

    $html = ob_get_contents();
    ob_end_clean();

    wp_send_json(['result' => $html]);
}

add_action('wp_ajax_archive_pagination', 'archive_pagination');
add_action('wp_ajax_nopriv_archive_pagination', 'archive_pagination');
