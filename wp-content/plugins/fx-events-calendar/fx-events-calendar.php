<?php
/*
 * Plugin name: FX Events Calendar
 * Author: Flexi
 * Varsion 1.0
 * Text Domain: fxevents
 */

use Includes\Ajax;
use Includes\Functions;
use Includes\MainSettings;
use Includes\InitScripts;
use Includes\Metabox;
use Includes\Shortcode;

if (!defined('ABSPATH')) exit;

define('PLUGIN_PATH', plugin_dir_path(__FILE__));
define('PLUGIN_URL', plugin_dir_url(__FILE__));

foreach (glob(PLUGIN_PATH . 'app/*.php') as $file) {
    include_once $file;
}

InitScripts::init();
Metabox::init();
MainSettings::init();
Shortcode::init();
Ajax::init();