<?php


spl_autoload_register(function ($classname) {

    $wppath = realpath( plugin_dir_path( __FILE__ ) . '../../' ) . DIRECTORY_SEPARATOR  . $classname . '.php';
    $wppath = str_replace('\\','/', $wppath);
    if (file_exists($wppath)) {
        include_once $wppath;
    }

});


