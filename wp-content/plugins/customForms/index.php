<?php
/*
Plugin Name: Custom Forms
Description: Custom Forms Plugin developed by Flexi IT company
Version: 1.0
Author: Flexi IT
Domain: customforms
*/

require_once(realpath( plugin_dir_path( __FILE__ )) . '/inc/loader/loader.php');

use inc\DataBase;
use inc\Form;
use inc\ShortCodes;
use inc\Entries;
use inc\SettingsTab;

define('PLUGIN_DIR', plugin_dir_path( __FILE__ ));

class Core {

    static function init()
    {
        self::pluginHooks();
        self::pluginClassesInit();
    }

    static function pluginClassesInit() {
        new DataBase();
        new ShortCodes();
        new SettingsTab();
    }

    static function pluginHooks() {
        add_action('wp_enqueue_scripts', [self::class, 'enqueueScripts']);
    }

    static function enqueueScripts()
    {
        wp_enqueue_style('app-css',   plugins_url(  'dest/css/app.css' , __FILE__ ), array(), '1.0');

        wp_enqueue_script('jquery');
        wp_enqueue_script('app-js',     plugins_url('dest/js/app.js', __FILE__ ),'', '', true);
        wp_localize_script('app-js', 'ajax_filter', array(
                'url' => admin_url('admin-ajax.php'),
            )
        );
    }

}

class Ajax {
    static function init() {
        add_action('wp_ajax_formSubmit', [Form::class, 'formSubmit']);
        add_action('wp_ajax_nopriv_formSubmit', [Form::class, 'formSubmit']);
        add_action('wp_ajax_getEntryMessage', [Entries::class, 'getEntryMessage']);
        add_action('wp_ajax_nopriv_getEntryMessage', [Entries::class, 'getEntryMessage']);
        add_action('wp_ajax_loadMoreEntries', [Entries::class, 'loadMoreEntries']);
        add_action('wp_ajax_nopriv_loadMoreEntries', [Entries::class, 'loadMoreEntries']);
    }
}

Core::init();
Ajax::init();
