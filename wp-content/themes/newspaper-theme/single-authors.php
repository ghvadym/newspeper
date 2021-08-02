<?php
get_header();
?>

    <article class="article authors">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) :the_post(); ?>
                <div class="article__head">
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title() ?>">
                </div>
                <div class="article__title">
                    <h1><?php the_title() ?></h1>
                </div>
            <?php endwhile; ?>
        <?php endif ?>
        <div class="article__news">
            <?php
            $post = get_post();

            $args = [
                'post_type'    => ['magazines', 'newspapers'],
                'numberposts'  => 4,
                'post_status'  => 'publish',
                'orderby'      => 'date',
                'order'        => 'desc',
                'meta_key' => 'author_name',
                'meta_value' => $post->ID,
            ];

            $posts = get_posts($args);
            if (!empty($posts)) :
                foreach ($posts as $post) : setup_postdata($post);
                    get_template_part_var('templates/components/recent-posts', ['post' => $post]);
                endforeach;
            else:
                ?>
                <h3><?php _e('Coming soon', 'newspaper'); ?></h3>
            <?php
            endif;
            wp_reset_postdata(); ?>
        </div>

        <?php $authorsPostsPage = get_field('authors_posts_page', 'options');
        $page = get_permalink($authorsPostsPage->ID); ?>
        <div class="author-posts">
            <a class="author-posts__link"
               href="<?php echo $page . '?id=' . get_the_ID() ?>">
                <?php _e('All Author posts', 'newspaper') ?>
            </a>
        </div>
    </article>

<?php
get_footer();
