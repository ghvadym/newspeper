<div class="post__item">
    <a href="<?php the_permalink(); ?>" class="post__thumb">
        <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
    </a>
    <div class="post__content">
        <div class="post__title">
            <?php echo strlen($post->post_title) > 40 ? substr($post->post_title, 0, 40) . '...' : $post->post_title ?>
        </div>
        <?php if(has_excerpt($post->ID)) : ?>
            <div class="post__desc">
                <?php echo strlen($post->post_excerpt) > 70 ? substr($post->post_excerpt, 0, 70) . '...' : $post->post_excerpt ?>
            </div>
        <?php endif; ?>

        <div class="post__label">
            <?php if (has_term('', 'label')) :
                $termsLabel = get_the_terms($post, 'label'); ?>
                <a class="label__mag" href="<?php echo get_term_link($termsLabel[0]->term_id, 'label') ?>">
                    <?php echo $termsLabel[0]->name ?>
                </a>
            <?php endif ?>

            <?php echo has_term('',  'categories') &&
                       has_term('', 'label') ? "<b> / </b>" : '' ; ?>

            <?php if (has_term('', 'categories')) :
                $termsCat = get_the_terms($post, 'categories'); ?>
                <a class="label__cat" href="<?php echo get_term_link($termsCat[0]->term_id, 'categories') ?>">
                    <?php echo $termsCat[0]->name ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</div>