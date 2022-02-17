<?php
get_header();
$fields = get_fields();
?>

    <article class="article home">
        <section class="intro">
            <?php if ($fields['home_title']) : ?>
                <div class="article__head" style="<?php echo getBgImage('girl_with_laptop.jpg') ?>">
                    <h1 class="article__title">
                        <?php echo $fields['home_title'] ?>
                    </h1>
                </div>
            <?php endif ?>
        </section>
        <section class="section">
            <div class="article__about">
                <?php if ($fields['home_subtitle']) : ?>
                    <h2 class="article__subtitle">
                        <?php echo $fields['home_subtitle'] ?>
                    </h2>
                <?php endif ?>
                <?php if ($fields['home_description']) : ?>
                    <div class="article__desc">
                        <?php echo $fields['home_description'] ?>
                    </div>
                <?php endif ?>
            </div>
        </section>
        <section class="section">
            <div class="article__partners">
                <h2>
                    <?php _e('A few of our favorites', 'newspaper') ?>
                </h2>
                <?php get_template_part('templates/components/partners') ?>
            </div>
        </section>
    </article>

    <section class="aside">
        <div class="post__list">
            <?php
            $args = [
                'post_type'   => ['magazines', 'newspapers'],
                'numberposts' => 3,
                'post_status' => 'publish',
                'orderby'     => 'date',
                'order'       => 'desc',
            ];
            getPosts($args)
            ?>
        </div>
    </section>

<?php get_footer();
