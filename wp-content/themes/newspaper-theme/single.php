<?php
get_header(); ?>

    <article class="article">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) :
                the_post(); ?>
                <div class="article__title">
                    <h1><?php the_title() ?></h1>
                    <blockquote><?php echo '&#10077' . get_the_excerpt() . '&#10077;' ?></blockquote>
                </div>
                <div class="article__head" style="background-image: url(<?php the_post_thumbnail_url(); ?>)"></div>
                <div class="article__body">
                    <?php the_content() ?>
                </div>
                <?php
                ?>
                <div class="article__data">
                    <div class="data__item">
                        <span>

                        </span>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php endif ?>
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
            if (!empty($posts)) :
                foreach ($posts as $post) : setup_postdata($post);
                    get_template_part_var('templates/components/recent-posts', ['post' => $post]);
                endforeach;
            else:
                ?>
                <h3><?php _e('Coming soon', 'newspaper'); ?></h3>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </aside>

<?php
get_footer();
