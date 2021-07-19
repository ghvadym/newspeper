<div class="post__item">
    <a href="<?php the_permalink(); ?>" class="post__body">
        <div class="post__thumb">
            <img width="100" src="<?php the_post_thumbnail_url(); ?>" alt="<?php echo $post->post_name ?>">
        </div>
        <div class="post__content">
            <div class="post__title">
                <?php echo strlen($post->post_title) > 50 ? substr($post->post_title, 0, 50) . '...' : $post->post_title ?>
            </div>
        </div>
    </a>
</div>

