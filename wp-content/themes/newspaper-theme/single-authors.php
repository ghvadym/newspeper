<?php
get_header();
$post = get_post();
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
            $args = [
                'post_type'   => ['magazines', 'newspapers'],
                'numberposts' => 4,
                'post_status' => 'publish',
                'orderby'     => 'date',
                'order'       => 'desc',
                'meta_key'    => 'author_name',
                'meta_value'  => $post->ID,
            ];
            getPosts($args) ?>
        </div>

        <?php $authorsPostsPage = get_field('authors_posts_page', 'options');
        if ($authorsPostsPage) :
            $page = get_permalink($authorsPostsPage->ID); ?>
            <div class="author-posts">
                <a class="author-posts__link"
                   href="<?php echo $page . '?id=' . $post->ID ?>">
                    <?php _e('All Author posts', 'newspaper') ?>
                </a>
            </div>
        <?php endif; ?>
    </article>

<?php
get_footer();
