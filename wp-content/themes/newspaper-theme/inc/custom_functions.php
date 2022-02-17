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

function wp_get_current_url()
{
    return home_url($_SERVER['REQUEST_URI']);
}

function getImage($name) : string
{
    return get_template_directory_uri() . '/assets/images/' . $name;
}

function getBgImage($name) : string
{
    $image = getImage($name);
    return 'background-image: url(' . $image . ')';
}

function textLimiter($text, $limit = 40)
{
    return strlen($text) > $limit ? substr($text, 0, $limit) . '...' : $text;
}

function getPosts($args)
{
    $posts = get_posts($args);
    if ($posts) {
        foreach ($posts as $post) {
            setup_postdata($post);
            get_template_part_var('templates/components/recent-posts', ['post' => $post]);
        }
    } else {
        echo '<h3>' . _e('Coming soon', 'newspaper') . '</h3>';
    }
    wp_reset_postdata();
}

function footerWidgets()
{
    $widgets = [
        'footer-logo',
        'footer-pages',
        'footer-labels-magazines',
        'footer-categories-magazines',
        'footer-labels-newspapers',
        'footer-categories-newspapers',
    ];
    foreach ($widgets as $widget) {
        if (is_active_sidebar($widget)) {
            dynamic_sidebar($widget);
        }
    }
}