<?php get_header();

$post = get_post();
$postType = $post->post_type;
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged'          => $paged,
    'post_type'      => 'newspapers',
    'tax_query'      => [
        [
            'taxonomy' => 'labels-newspapers',
            'operator' => 'EXISTS',
        ],
        [
            'taxonomy' => 'categories-newspapers',
            'operator' => 'EXISTS',
        ]
    ],
];

$query = new WP_Query($args); ?>

    <article class="archive">
        <?php if ($query->have_posts()) : ?>
            <div class="post__list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="post__item">
                        <a href="<?php the_permalink(); ?>" class="post__thumb">
                            <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
                        </a>
                        <div class="post__content">
                            <div class="post__title">
                                <?php echo textLimiter($post->post_title) ?>
                            </div>
                            <?php if(has_excerpt($post->ID)) : ?>
                                <div class="post__desc">
                                    <?php echo textLimiter($post->post_excerpt, 70) ?>
                                </div>
                            <?php endif; ?>

                            <div class="post__label">
                                <?php if (has_term('', 'labels-newspapers')) :
                                    $termsLabel = get_the_terms($post, 'labels-newspapers'); ?>
                                    <a class="label__mag" href="<?php echo get_term_link($termsLabel[0]->term_id, 'labels-newspapers') ?>">
                                        <?php echo $termsLabel[0]->name ?>
                                    </a>
                                <?php endif ?>

                                <?php if (has_term('', 'categories-newspapers')) :
                                    $termsCat = get_the_terms($post, 'categories-newspapers'); ?>
                                    <a class="label__cat" href="<?php echo get_term_link($termsCat[0]->term_id, 'categories-newspapers') ?>">
                                        <?php echo $termsCat[0]->name ?>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile ?>
            </div>
        <?php endif ?>
        <?php wp_reset_postdata() ?>

        <div class="pagination" data-post="<?php echo $postType ?>">
            <?php echo paginate_links([
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'prev_next' => false,
                'show_all'  => true,
            ]); ?>
        </div>
    </article>

<?php get_footer();
