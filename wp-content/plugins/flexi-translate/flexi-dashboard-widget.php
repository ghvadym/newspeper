<?php
/*
Plugin Name: Flexi Translate
Plugin URI:
Description: Translate Plugin from Flexi IT company
Version: 1.0
Author: Flexi IT
Domain: flexi
*/


require_once('loader.php');
spl_autoload_register( 'simplarity_autoloader' );

$dirs = [glob(dirname(__FILE__) . '/inc/*/*.php')];

\Helpers\Translate_Core::initClasses($dirs, plugin_dir_url(__FILE__));


add_action( 'admin_bar_menu', 'my_admin_bar_menu', 1000 );
function my_admin_bar_menu( $wp_admin_bar )
{
    $wp_admin_bar->add_menu(array(
        'id' => 'addtranslate',
        'title' => 'Перевести',
        'href' => '#',
    ));
};