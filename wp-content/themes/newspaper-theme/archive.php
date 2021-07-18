<?php get_header();

$post = get_post();
$paged = ( get_query_var('page') ? get_query_var('page') : 1 );
$args = [
    'posts_per_page' => get_option( 'posts_per_page' ),
    'post_type'      => get_post_type( $post->ID ),
    'paged'          => $paged
];

$query = new WP_Query( $args );
?>

<article class="archive">
    <?php if ( $query->have_posts() ) : ?>
        <div class="post__list">
            <?php while ($query->have_posts()) : $query->the_post(); ?>
                <?php
                $termsLabel = get_the_terms( $post, 'label' );
                $termsCat = get_the_terms( $post, 'categories' );
                ?>

                <div class="post__item">
                    <a href="<?php the_permalink(); ?>" class="post__thumb">
                        <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
                    </a>
                    <div class="post__content">
                        <div class="post__title">
                            <?php echo strlen( $post->post_title ) > 60 ? substr( $post->post_title , 0, 60) . '...' : $post->post_title ?>
                        </div>
                        <div class="post__desc">
                            <?php echo strlen( $post->post_excerpt ) > 100 ? substr( $post->post_excerpt, 0, 100 ) . '...' : $post->post_excerpt ?>
                        </div>
                        <?php if ( !empty( $termsLabel ) || !empty( $termsCat ) ) : ?>
                            <div class="post__label">
                                <a href="<?php echo get_term_link($termsLabel[0]->term_id, 'label') ?>">
                                    <?php echo $termsLabel[0]->name ?>
                                </a>
                                |
                                <a href="<?php echo get_term_link($termsCat[0]->term_id, 'categories') ?>">
                                    <?php echo $termsCat[0]->name ?>
                                </a>
                            </div>
                        <?php endif ?>
                    </div>
                </div>

            <?php endwhile ?>
        </div>
    <?php endif ?>
    <?php  wp_reset_postdata() ?>

    <div class="pagination">
        <?php echo paginate_links([
            'current'   => $paged,
            'total'     => $query->max_num_pages,
            'format'    => '?page=%#%',
            'prev_next' => true,
            'prev_text' => '<',
            'next_text' => '>',
        ]); ?>

    </div>
</article>

<?php get_footer();
