<?php
get_header();
/*
 * Template name: Posts Author
 */

$post = get_post();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'paged'          => $paged,
    'post_type'      => ['magazines', 'newspapers'],
];

$personId = $_GET['id'] ?? '';
if ($personId) {
    $args['meta_key'] = 'author_name';
    $args['meta_value'] = $personId;
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
                'show_all'  => true,
            ]); ?>
        </div>
    </article>

<?php
get_footer();
