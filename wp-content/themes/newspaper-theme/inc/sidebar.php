<?php

add_action('widgets_init', 'custom_sidebar');
function custom_sidebar()
{
    register_my_sidebar('Footer Logo', 'footer-logo');
    register_my_sidebar('Footer Pages', 'footer-pages');
    register_my_sidebar('Footer Labels Newspapers', 'footer-labels-magazines');
    register_my_sidebar('Footer Categories Magazines', 'footer-categories-magazines');
    register_my_sidebar('Footer Labels Newspapers', 'footer-labels-newspapers');
    register_my_sidebar('Footer Categories Newspapers', 'footer-categories-newspapers');
}

function register_my_sidebar($title, $slug)
{
    register_sidebar([
        'name'          => $title,
        'id'            => $slug,
        'description'   => '',
        'class'         => '',
        'before_widget' => '<div class="footer-column col-md-6 col-lg-4">',
        'after_widget'  => "</div>\n",
        'before_title'  => '<h2 class="widget__title">',
        'after_title'   => "</h2>\n",
    ]);
}