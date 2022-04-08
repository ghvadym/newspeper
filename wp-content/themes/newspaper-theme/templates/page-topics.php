<?php
/*
  * Template name: Topics
  */
get_header();
$paged = (get_query_var('page') ? get_query_var('page') : 1);
$args = [
    'post_status'    => 'publish',
    'posts_per_page' => get_option('posts_per_page'),
    'post_type'      => ['magazines', 'newspapers'],
    'paged'          => $paged,
];
$query = new WP_Query($args);
?>

    <aside class="aside">
        <div class="filter__head">
            <h2 class="filter__title"><?php _e('Use Filter', 'newspaper') ?></h2>
            <div class="filter__btn">
                <?php get_template_part('/assets/icons/svg', 'pin') ?>
            </div>
        </div>


        <?php
        $data = [
            "Magazines Labels"      => 'label',
            "Magazines Categories"  => 'categories',
            "Newspapers Labels"     => 'labels-newspapers',
            "Newspapers Categories" => 'categories-newspapers',
        ];
        foreach ($data as $key => $value) {
            $data[$key] = get_terms($value, ['hide_empty' => true]);
        }
        ?>

        <div class="news-filter">
            <?php foreach ($data as $title => $terms) : ?>
                <?php if ($terms) : ?>
                    <div class="news-filter__cat">
                        <h3 class="news-filter__title"><?php _e($title, 'newspaper') ?></h3>
                        <div class="news-filter__list">
                            <?php foreach ($terms as $term) : ?>
                                <label class="news-filter__item">
                                    <input type="checkbox" value="<?php echo $term->term_id ?>">
                                    <?php echo $term->name ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <a href="<?php wp_get_current_url() ?>" class="news-filter__clear"><?php _e('Clear filter', 'newspaper') ?></a>
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

        <div class="pagination" data-terms="0">
            <?php echo paginate_links([
                'current'   => $paged,
                'total'     => $query->max_num_pages,
                'show_all'  => true,
                'prev_next' => false,
            ]); ?>
        </div>
    </article>

<?php get_footer(); ?>