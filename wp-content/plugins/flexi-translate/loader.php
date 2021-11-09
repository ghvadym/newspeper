<?php

function simplarity_autoloader(  $class_name ){
    $arr = ['Helpers\Fetch_Response','Helpers\Translate_Core', 'Main\Main_Page', 'Tabs\Tabs_Enable', 'Helpers\Functions', 'Helpers\RecursiveDOMIterator', 'MetaBox\CustomMetaBox', 'Scripts\EnqueueScripts', 'Helpers\CreateTranslatedPost'];

    if (!in_array($class_name, $arr)) {
        return;
    }

    $classes_dir = realpath( plugin_dir_path( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR;
    $class_file = $class_name . '.php';
    require_once str_replace('\\', '/', $classes_dir . $class_file);
}