<?php get_header();

$post = get_post();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged'          => $paged,
    'post_type'      => get_post_type($post->ID),
];

switch (get_post_type(get_the_ID())) {
    case 'magazines' :
        $taxQuery = [
            [
                'taxonomy' => 'label',
                'operator' => 'EXISTS',
            ],
            [
                'taxonomy' => 'categories',
                'operator' => 'EXISTS',
            ],
        ];
        break;
    case 'newspapers' :
        $taxQuery = [
            [
                'taxonomy' => 'labels-newspapers',
                'operator' => 'EXISTS',
            ],
            [
                'taxonomy' => 'categories-newspapers',
                'operator' => 'EXISTS',
            ],
        ];
        break;
}

switch (get_query_var('taxonomy')) {
    case 'label' :
        $taxQuery[] = [
            [
                'taxonomy' => 'label',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
    case 'categories' :
        $taxQuery[] = [
            [
                'taxonomy' => 'categories',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
    case 'labels-newspapers' :
        $taxQuery[] = [
            [
                'taxonomy' => 'labels-newspapers',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
    case 'categories-newspapers' :
        $taxQuery[] = [
            [
                'taxonomy' => 'categories-newspapers',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
}

$args['tax_query'] = $taxQuery;

$query = new WP_Query($args); ?>

    <article class="archive">
        <h1 class="archive__title"><?php echo get_the_archive_title() ?></h1>
        <?php if ($query->have_posts()) : ?>
            <div class="post__list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php get_template_part_var('templates/components/archive-posts', ['post' => $post]); ?>
                <?php endwhile ?>
            </div>
        <?php else: ?>
            <?php _e('Coming Soon', 'newspaper') ?>
        <?php endif ?>
        <?php wp_reset_postdata() ?>

        <div class="pagination">
            <?php echo paginate_links([
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'prev_next' => false,
            ]); ?>
        </div>
    </article>

<?php get_footer();
