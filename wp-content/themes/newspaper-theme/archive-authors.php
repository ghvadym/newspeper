<?php get_header();

$post = get_post();
$args = [
    'post_type'   => 'authors',
    'numberposts' => -1,
    'post_status' => 'publish',
];
$posts = get_posts($args); ?>

    <article class="archive authors">
        <?php if (!empty($posts)) : ?>
            <div class="post__list">
                <?php foreach ($posts as $post) : setup_postdata($post); ?>
                    <div class="post__item">
                        <a href="<?php the_permalink(); ?>" class="post__thumb">
                            <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
                        </a>
                        <div class="post__content">
                            <div class="post__title">
                                <?php echo strlen($post->post_title) > 40 ? substr($post->post_title, 0, 40) . '...' : $post->post_title ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </article>

<?php get_footer();
