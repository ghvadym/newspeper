<?php
get_header();
$fields = get_fields();
?>

    <article class="article home">
        <div class="intro">
            <div class="article__head" style="background-image: url(<?php echo get_template_directory_uri() . '/assets/images/girl_with_laptop.jpg' ?>)">
                <div class="article__title">
                    <h1>
                        <?php echo $fields['home_title'] ?>
                    </h1>
                </div>
            </div>
        </div>
        <div class="section">
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
        </div>
        <div class="section">
            <div class="article__partners">
                <h2>
                    <?php _e('A few of our favorites', 'newspaper') ?>
                </h2>
                <?php get_template_part('templates/components/partners') ?>
            </div>
        </div>
    </article>

    <aside class="aside">
        <div class="post__list">
            <?php
            $post = get_post();
            $args = [
                'post_type'   => ['magazines', 'newspapers'],
                'numberposts' => 3,
                'post_status' => 'publish',
                'orderby'     => 'date',
                'order'       => 'desc',
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
    </aside>

<?php get_footer();
