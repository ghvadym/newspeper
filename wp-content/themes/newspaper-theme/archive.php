<?php
get_header();
$post = get_post();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged'          => $paged,
    'post_type'      => get_post_type($post->ID),
];

$term = get_query_var('term');
$taxonomy = get_query_var('taxonomy');
$taxonomies = get_object_taxonomies($post->post_type);
$taxQuery = [
    [
        'taxonomy' => $taxonomy,
        'field'    => 'slug',
        'terms'    => $term,
    ],
];

foreach ($taxonomies as $taxonomy) {
    $taxQuery[] = [
        [
            'taxonomy' => $taxonomy,
            'operator' => 'EXISTS',
        ],
    ];
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

        <div class="pagination" data-terms="0">
            <?php echo paginate_links([
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'prev_next' => false,
            ]); ?>
        </div>
    </article>

<?php
get_footer();
