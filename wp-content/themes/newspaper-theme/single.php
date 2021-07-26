<?php
get_header();
?>

    <article class="article">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) :the_post(); ?>
                <div class="article__title">
                    <h1><?php the_title() ?></h1>
                    <?php if (get_the_excerpt()) : ?>
                        <blockquote><?php echo '&#10077 ' . get_the_excerpt() . ' &#10077;' ?></blockquote>
                    <?php endif; ?>
                </div>
                <div class="article__head" style="background-image: url(<?php the_post_thumbnail_url(); ?>)"></div>
                <div class="article__body">
                    <?php the_content() ?>
                </div>
                <div class="article__data">
                    <?php if (has_term('', 'label')): ?>
                        <div class="data__item">
                            <?php _e('Label: ', 'newspaper') . the_terms(get_the_ID(), 'label') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (has_term('', 'categories')): ?>
                        <div class="data__item">
                            <?php _e('Categories: ', 'newspaper') . the_terms(get_the_ID(), 'categories') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (get_field('author_name', get_the_ID())) :
                        $author = get_field('author_name', get_the_ID()) ?>
                        <div class="data__item">
                            <?php _e('Author: ', 'newspaper') ?>
                            <a href="<?php echo get_permalink($author->ID) ?>">
                                <?php echo $author->post_title ?>
                            </a>
                        </div>
                    <?php endif; ?>
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
