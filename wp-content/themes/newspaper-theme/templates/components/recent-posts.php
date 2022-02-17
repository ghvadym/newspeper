<div class="post__item">
    <a href="<?php the_permalink($post->ID); ?>" class="post__body">
        <div class="post__thumb">
            <img width="100" src="<?php echo get_the_post_thumbnail_url($post); ?>" alt="<?php echo $post->post_name ?>">
        </div>
        <div class="post__content">
            <div class="post__title">
                <?php echo textLimiter($post->post_title, 50) ?>
            </div>
        </div>
    </a>
</div>

