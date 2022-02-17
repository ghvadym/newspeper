<?php
get_header(); ?>

    <div class="page-404">
        <div class="container">
            <div class="page-404__wrap">
                <?php echo get_field('content_404','options') ?>
            </div>
        </div>
    </div>

<?php
get_footer();