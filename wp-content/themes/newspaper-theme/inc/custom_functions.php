<?php

if (!function_exists('dd')) {
    function dd()
    {
        echo '<pre>';
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());
        die;
    }
}

function get_template_part_var($template, $data = [])
{
    extract($data);
    require locate_template($template . '.php');
}

add_action('the_content','wrap_content_div');
function wrap_content_div( $content )
{
    return '<div class="article__content">'.$content.'</div>';
}

function wp_get_current_url() {
    return home_url( $_SERVER['REQUEST_URI'] );
}

add_filter('get_the_archive_title', function( $title ){
    return preg_replace('~^[^:]+: ~', '', $title );
});