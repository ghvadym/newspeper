<?php get_header();

$post = get_post();
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

/*Taxonomy Term page*/
switch (get_query_var('taxonomy')) {
    case 'labels-newspapers' :
        $args['tax_query'] = [
            [
                'taxonomy' => 'label',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
    case 'categories-newspapers' :
        $args['tax_query'] = [
            [
                'taxonomy' => 'categories',
                'field'    => 'slug',
                'terms'    => get_query_var('term'),
            ],
        ];
        break;
}

$query = new WP_Query($args); ?>

    <article class="archive">
        <?php if ($query->have_posts()) : ?>
            <div class="post__list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php get_template_part_var('templates/components/archive-posts', ['post' => $post]); ?>
                <?php endwhile ?>
            </div>
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
