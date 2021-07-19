<?php
get_header(); ?>

    <article class="article">

    </article>

    <aside class="aside">
        <div class="post__list">
            <?php
            $post = get_post();

            $args = [
                'post_type'    => 'news',
                'numberposts'  => 2,
                'post_status'  => 'publish',
                'orderby'      => 'date',
                'order'        => 'desc',
                'post__not_in' => [$post->ID],
            ];

            $terms = get_the_terms($post, 'categories');
            if (get_post_type($post->ID) == 'news') {
                $args['tax_query'] = [
                    [
                        'taxonomy' => 'categories',
                        'field'    => 'slug',
                        'terms'    => $terms[0],
                    ],
                ];
            }

            $posts = get_posts($args);
            foreach ($posts as $post) : setup_postdata($post);
                get_template_part_var('templates/components/recent-posts', ['post' => $post]);
            endforeach;
            wp_reset_postdata();
            ?>
        </div>
    </aside>

<?php
get_footer();
