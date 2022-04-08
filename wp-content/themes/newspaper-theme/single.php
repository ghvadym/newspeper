<?php
get_header();
$post = get_post();
?>

    <article class="article news">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) :the_post(); ?>
                <div class="article__title">
                    <h1><?php the_title() ?></h1>
                    <?php if (has_excerpt($post->ID)) : ?>
                        <blockquote><?php echo '&#10077 ' . get_the_excerpt() . ' &#10077;' ?></blockquote>
                    <?php endif; ?>
                </div>
                <div class="article__head">
                    <img src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title() ?>">
                </div>
                <div class="article__speech">
                    <div class="speech" data-id="<?php echo $post->ID ?>">
                        <?php _e('Text to Speech', 'newspaper') ?>
                    </div>
                    <div class="speech-result"></div>
                </div>
                <div class="article__body">
                    <?php the_content() ?>
                </div>
                <div class="article__data">
                    <?php if (get_post_type($post->ID) === 'newspapers') : ?>

                        <?php if (has_term('', 'labels-newspapers')): ?>
                            <div class="data__item">
                                <?php _e('Label: ', 'newspaper') . the_terms($post->ID, 'labels-newspapers') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_term('', 'categories-newspapers')): ?>
                            <div class="data__item">
                                <?php _e('Categories: ', 'newspaper') . the_terms($post->ID, 'categories-newspapers') ?>
                            </div>
                        <?php endif; ?>

                    <?php elseif (get_post_type($post->ID) === 'magazines') : ?>

                        <?php if (has_term('', 'label')): ?>
                            <div class="data__item">
                                <?php _e('Label: ', 'newspaper') . the_terms($post->ID, 'label') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (has_term('', 'categories')): ?>
                            <div class="data__item">
                                <?php _e('Categories: ', 'newspaper') . the_terms($post->ID, 'categories') ?>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>

                    <?php
                    $author = get_field('author_name', $post->ID);
                    if ($author) : ?>
                        <div class="data__item">
                            <?php _e('Author: ', 'newspaper') ?>
                            <a href="<?php echo get_permalink($author->ID) ?>">
                                <?php echo $author->post_title ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php endif ?>
    </article>

    <aside class="aside">
        <div class="post__list">
            <?php
            $args = [
                'post_type'    => $post->post_type,
                'numberposts'  => 2,
                'post_status'  => 'publish',
                'orderby'      => 'date',
                'order'        => 'desc',
                'post__not_in' => [$post->ID],
            ];
            getPosts($args) ?>
        </div>
    </aside>

<?php
get_footer();
