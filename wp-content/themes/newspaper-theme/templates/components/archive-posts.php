<div class="post__item">
    <a href="<?php the_permalink(); ?>" class="post__thumb">
        <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
    </a>
    <div class="post__content">
        <div class="post__title">
            <?php echo textLimiter($post->post_title) ?>
        </div>
        <?php if(has_excerpt($post->ID)) : ?>
            <div class="post__desc">
                <?php echo textLimiter($post->post_excerpt, 70) ?>
            </div>
        <?php endif; ?>

        <div class="post__label">
            <?php if (has_term('', 'label')) :
                $termsLabel = get_the_terms($post, 'label'); ?>
                <a class="label__mag" href="<?php echo get_term_link($termsLabel[0]->term_id, 'label') ?>">
                    <?php echo $termsLabel[0]->name ?>
                </a>
            <?php endif ?>
            <?php if (has_term('', 'labels-newspapers')) :
                $termsLabel = get_the_terms($post, 'labels-newspapers'); ?>
                <a class="label__mag" href="<?php echo get_term_link($termsLabel[0]->term_id, 'labels-newspapers') ?>">
                    <?php echo $termsLabel[0]->name ?>
                </a>
            <?php endif ?>

            <?php if (has_term('', 'categories')) :
                $termsCat = get_the_terms($post, 'categories'); ?>
                <a class="label__cat" href="<?php echo get_term_link($termsCat[0]->term_id, 'categories') ?>">
                    <?php echo $termsCat[0]->name ?>
                </a>
            <?php endif ?>
            <?php if (has_term('', 'categories-newspapers')) :
                $termsCat = get_the_terms($post, 'categories-newspapers'); ?>
                <a class="label__cat" href="<?php echo get_term_link($termsCat[0]->term_id, 'categories-newspapers') ?>">
                    <?php echo $termsCat[0]->name ?>
                </a>
            <?php endif ?>
        </div>
    </div>
</div>