<?php
get_header();
$fields = get_fields();
?>

    <article class="article">
        <div class="article__head" style="background-image: url(<?php echo get_template_directory_uri() . '/assets/images/girl_with_laptop.jpg' ?>)">
            <div class="article__title">
                <h1>
                    <?php echo $fields['home_title'] ?>
                </h1>
            </div>
        </div>
        <div class="article__about">
            <div class="article__subtitle">
                <h2>
                    <?php echo $fields['home_subtitle'] ?>
                </h2>
            </div>
            <div class="article__desc">
                <?php echo $fields['home_description'] ?>
            </div>
        </div>
        <div class="article__partners">
            <h2>
                <?php _e('A few of our favorites', 'newspaper') ?>
            </h2>
            <?php get_template_part('templates/components/partners') ?>
        </div>
    </article>

    <aside class="aside">
        <div class="post__list">
            <?php
            $post = get_post();
            $args = [
                'post_type'   => 'news',
                'numberposts' => 3,
                'post_status' => 'publish',
                'orderby'     => 'date',
                'order'       => 'desc',
            ];
            $posts = get_posts($args);
            foreach ($posts as $post) : setup_postdata($post);
                get_template_part_var('templates/components/recent-posts', ['post' => $post]);
            endforeach;
            wp_reset_postdata(); ?>
        </div>
    </aside>

<?php get_footer();
