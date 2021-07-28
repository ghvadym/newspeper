<?php get_header();

$post = get_post();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged'          => $paged,
];

if (is_archive()) {
    $args['post_type'] = get_post_type($post->ID);
}

$personId = $_GET['id'] ?? '';
if ($personId) {
    $args['meta_key'] = 'author_name';
    $args['meta_value'] = $personId;
}

$term_list = wp_get_post_terms($post->ID, 'label', array("fields" => "all"));

if (is_tax()) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'label',
            'field'    => 'slug',
            'terms'    => $term_list[0]->slug,
        ]
    ];
}

$query = new WP_Query($args);
?>

    <article class="archive <?php echo get_post_type($post->ID) === 'authors' ? 'authors' : '' ?>">
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
                'format'    => '?page=%#%',
                'prev_next' => false,
            ]); ?>
        </div>
    </article>

<?php get_footer();
