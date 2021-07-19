<?php
get_header();
$fields = get_fields();
?>

    <article class="article">
        <div class="article__head">
            <div class="article__title">
                <h1>
                    <?php echo $fields['home_title'] ?>
                </h1>
            </div>
        </div>
        <div class="article__about">
            <div class="article__subtitle">
                <h2>
                    <?php echo $fields['home_subtitle'] ?>
                </h2>
            </div>
            <div class="article__desc">
                <?php echo $fields['home_description'] ?>
            </div>
        </div>
        <div class="article__partners">
            <h2>
                <?php _e('A few of our favorites', 'newspaper') ?>
            </h2>
            <?php get_template_part('templates/components/partners') ?>
        </div>
    </article>

    <aside class="aside">
        <div class="post__list">
            <?php get_template_part('templates/components/recent-posts') ?>
        </div>
    </aside>

<?php get_footer();
