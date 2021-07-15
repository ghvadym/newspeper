<?php get_header();

$args = [
    'post_type'   => 'news',
    'numberposts' => 3,
    'post_status' => 'publish',
    'orderby'     => 'date',
    'order'       => 'asc'
];
$posts = get_posts($args);
//dd($posts);

$fields = get_fields();
?>

<article class="article">
    <div class="article__head">
        <div class="article__title">
            <h1>
                <?php echo $fields['home_title'] ?>
            </h1>
        </div>
    </div>
    <div class="article__body">
        <div class="article__subtitle">
            <h2>
                <?php echo $fields['home_subtitle'] ?>
            </h2>
        </div>
        <div class="article__desc">
            <?php echo $fields['home_description'] ?>
        </div>
    </div>
</article>

<aside class="aside">
    <div class="post__list">
        <?php foreach( $posts as $post ) : setup_postdata($post); ?>
            <div class="post__item">
                <a href="<?php the_permalink(); ?>" class="post__body">
                    <div class="post__thumb">
                        <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
                    </div>
                    <div class="post__title">
                        <span>
                            <?php echo strlen($post->post_title) > 50 ? substr( $post->post_title, 0, 50 ) . '...' : $post->post_title ?>
                        </span>
                    </div>
                </a>
            </div>
        <?php endforeach; wp_reset_postdata(); ?>
    </div>
</aside>

<?php get_footer();
