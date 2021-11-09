<?php


namespace Helpers;


class Translate_Core
{
    static function initClasses($dir, $location) {
            foreach($dir[0] as $file){
                preg_match('/^.*(\/.*\/.*)/', $file, $matches);
                $class = $matches[1];
                $class = str_replace('/', '\\', $class);
                $class = preg_replace('/.php/', '', $class);
                $arr = ['\Helpers\Fetch_Response', '\Main\Main_Page', '\Tabs\Tabs_Enable', '\Helpers\Functions', '\MetaBox\CustomMetaBox', '\Scripts\EnqueueScripts', '\Helpers\CreateTranslatedPost', '\Helpers\Translate_Core'];
                if (!in_array($class, $arr)) {
                    continue;
                }
                if ($class === '\Scripts\EnqueueScripts') {
                    new $class($location);
                    continue;
                }
                new $class;
            }
        }
}