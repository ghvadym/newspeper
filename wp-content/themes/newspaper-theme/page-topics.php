<?php
/*
  * Template name: Topics
  */
get_header();

$labels = get_terms('label', ['hide_empty' => true]);
$categories = get_terms('categories', ['hide_empty' => true]);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => 9,
    'post_type'      => 'magazines',
    'tax_query'      => [
        [
            'taxonomy' => 'label',
            'operator' => 'EXISTS',
        ],
        [
            'taxonomy' => 'categories',
            'operator' => 'EXISTS',
        ]
    ],
];
$query = new WP_Query($args);
?>

    <aside class="aside">
        <div class="filter__head">
            <h2 class="filter__title">Use Filter</h2>
            <div class="filter__btn">
                <?php get_template_part('/assets/icons/svg', 'pin') ?>
            </div>
        </div>
        <div class="news-filter">
            <div class="news-filter__cat">
                <?php if (!empty($labels)) : ?>
                <h2 class="news-filter__title"><?php _e('Labels', 'newspaper') ?></h2>
                <div class="news-filter__list">
                    <?php foreach ($labels as $label) : ?>
                        <label class="news-filter__item">
                            <input type="checkbox" value="<?php echo $label->term_id ?>">
                            <?php echo $label->name ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($categories)) : ?>
                <div class="news-filter__cat">
                    <h2 class="news-filter__title"><?php _e('Categories', 'newspaper') ?></h2>
                    <div class="news-filter__list">
                        <?php foreach ($categories as $category) : ?>
                            <label class="news-filter__item">
                                <input type="checkbox" value="<?php echo $category->term_id ?>">
                                <?php echo $category->name ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <a href="<?php wp_get_current_url() ?>" class="news-filter__clear">Clear filter</a>
            <div class="btn-close">
                <?php get_template_part('/assets/icons/svg', 'close') ?>
            </div>
        </div>
    </aside>

    <article class="archive filter">
        <?php if ($query->have_posts()) : ?>
            <div class="post__list">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <?php get_template_part_var('templates/components/archive-posts', ['post' => $query->post]); ?>
                <?php endwhile ?>
            </div>
        <?php endif ?>
        <?php wp_reset_postdata() ?>
    </article>

<?php get_footer(); ?>