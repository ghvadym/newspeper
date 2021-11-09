<?php

namespace Includes;

class Shortcode
{
    public static function init()
    {
        add_shortcode('fx_event_calendar', [self::class, 'getCalendar']);
    }

    public static function getCalendar()
    {
        require Functions::getPath('calendar');
    }
}