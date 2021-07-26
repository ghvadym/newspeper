<?php
get_header();
?>

    <article class="article authors">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) :the_post(); ?>
                <div class="article__head" style="background-image: url(<?php the_post_thumbnail_url(); ?>)"></div>
                <div class="article__title">
                    <h1><?php the_title() ?></h1>
                    <?php if (get_the_excerpt()) : ?>
                        <blockquote><?php echo '&#10077 ' . get_the_excerpt() . ' &#10077;' ?></blockquote>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif ?>
        <div class="article__news">
            <?php
            $post = get_post();

            $args = [
                'post_type'    => 'news',
                'numberposts'  => 9,
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
    </article>

<?php
get_footer();
