<?php

namespace Includes;

class InitScripts
{
    public static function init()
    {
        add_action('wp_enqueue_scripts', [self::class, 'theme_scripts']);
        add_action('admin_enqueue_scripts', [self::class, 'admin_scripts']);
    }

    public static function theme_scripts()
    {
        wp_enqueue_style('fx-events-styles', PLUGIN_URL . 'assets/css/app.css', [], time());
        wp_enqueue_script('jquery');
        wp_enqueue_script('fx-events-scripts', PLUGIN_URL . 'assets/js/app.js', ['jquery'], time(), true);
        wp_localize_script('fx-events-scripts', 'fx_theme_ajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }

    public static function admin_scripts()
    {
        //wp_enqueue_style('fx-admin-styles', PLUGIN_URL . 'assets/css/admin.css', [], time());
        wp_enqueue_script('jquery');
        wp_enqueue_script('fx-admin-scripts', PLUGIN_URL . 'assets/js/admin.js', ['jquery'], time(), true);
        wp_localize_script('fx-admin-scripts', 'fx_admin_ajax', ['ajaxurl' => admin_url('admin-ajax.php')]);
    }
}