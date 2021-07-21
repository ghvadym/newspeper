<?php
/*
  * Template name: Magazines
  */
get_header();

$labels = get_terms('label', ['hide_empty' => true]);
$categories = get_terms('categories', ['hide_empty' => true]);
$args = [
    'post_type'   => 'authors',
    'numberposts' => -1,
    'post_status' => 'publish',
];
$posts = get_posts($args);
?>

<div class="aside">
    <div class="news-filter">
        <?php if (!empty($labels)) : ?>
            <div class="news-filter__cat">
                <h2 class="news-filter__title"><?php _e('Labels', 'newspaper') ?></h2>
                <div class="news-filter__list">
                    <?php foreach ($labels as $label) : ?>
                        <div class="news-filter__item">
                            <a href="<?php echo get_term_link($label->term_id) ?>"><?php echo $label->name ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($categories)) : ?>
            <div class="news-filter__cat">
                <h2 class="news-filter__title"><?php _e('Categories', 'newspaper') ?></h2>
                <div class="news-filter__list">
                    <?php foreach ($categories as $category) : ?>
                        <div class="news-filter__item">
                            <a href="<?php echo get_term_link($category->term_id) ?>"><?php echo $category->name ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($posts)) : ?>
            <div class="news-filter__cat">
                <h2 class="news-filter__title"><?php _e('Authors', 'newspaper') ?></h2>
                <div class="news-filter__list">
                    <?php foreach ($posts as $post) : setup_postdata($post); ?>
                        <div class="news-filter__item">
                            <a href="<?php the_permalink(); ?>"><?php echo $post->post_title ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif;
        wp_reset_postdata(); ?>
    </div>
</div>

<?php get_footer(); ?>