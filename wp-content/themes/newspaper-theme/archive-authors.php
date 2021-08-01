<?php get_header();

$post = get_post();
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'post_type'      => 'authors',
];

$query = new WP_Query($args); ?>

    <article class="archive <?php echo get_post_type($post->ID) === 'authors' ? 'authors' : '' ?>">
        <?php if ($query->have_posts()) : ?>
            <div class="post__list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php get_template_part_var('templates/components/archive-posts', ['post' => $post]); ?>
                <?php endwhile ?>
            </div>
        <?php endif ?>
        <?php wp_reset_postdata() ?>
    </article>

<?php get_footer();
