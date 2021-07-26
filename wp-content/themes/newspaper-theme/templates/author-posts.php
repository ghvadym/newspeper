<?php
/*
  * Template name: Author posts
  */
?>

<?php get_header();

$post = get_post();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'post_type'      => 'news',
    'paged'          => $paged,
];

$authorID = $_GET['id'] ?? '';

if ($authorID) {
    $args['meta_key'] = 'author_name';
    $args['meta_value'] = $authorID;
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
                'prev_next' => true,
                'prev_text' => '<',
                'next_text' => '>',
            ]); ?>

        </div>
    </article>

<?php get_footer();

