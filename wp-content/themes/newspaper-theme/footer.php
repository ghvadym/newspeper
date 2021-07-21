<?php wp_footer();
$labels = get_terms('label', ['hide_empty' => true]);
$categories = get_terms('categories', ['hide_empty' => true]);
?>

</main>

<div id="footer">
    <div class="footer__nav">
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="footer__logo">
                    <a class="logo" href="<?php echo home_url(); ?>">
                        Newspapers & <br>
                        Magazines
                    </a>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="nav__col">
                    <h2><?php _e('Pages', 'newspaper') ?></h2>
                    <?php wp_nav_menu(['theme_location' => 'main_header']) ?>
                </div>
            </div>
            <?php if (!empty($labels)) : ?>
                <div class="col-md-6 col-lg-3">
                    <h2><?php _e('Labels', 'newspaper') ?></h2>
                    <div class="nav__col">
                        <?php foreach ($labels as $label) : ?>
                            <div class="nav__item">
                                <a href="<?php echo get_term_link($label->term_id) ?>"><?php echo $label->name ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <?php if (!empty($categories)) : ?>
                <div class="col-md-6 col-lg-3">
                    <h2><?php _e('Categories', 'newspaper') ?></h2>
                    <div class="nav__col">
                        <?php foreach ($categories as $cat) : ?>
                            <div class="nav__item">
                                <a href="<?php echo get_term_link($cat->term_id) ?>"><?php echo $cat->name ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>