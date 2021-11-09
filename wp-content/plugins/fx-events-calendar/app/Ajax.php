<?php

namespace Includes;

class Ajax
{
    static function init()
    {
        add_action('wp_ajax_month_days', [Functions::class, 'getDaysOfMonth']);
        add_action('wp_ajax_nopriv_month_days', [Functions::class, 'getDaysOfMonth']);
    }
}