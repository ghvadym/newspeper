<?php
get_header();
$post = get_post();
?>

    <article class="archive authors">
        <?php if (!empty($posts)) : ?>
            <div class="post__list">
                <?php $args = [
                    'post_type'   => 'authors',
                    'numberposts' => -1,
                    'post_status' => 'publish',
                ];
                getPosts($args)
                ?>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </article>

<?php get_footer();
