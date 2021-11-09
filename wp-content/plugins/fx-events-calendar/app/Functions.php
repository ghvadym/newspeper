<?php

namespace Includes;

class Functions
{
    public static function getPath($fileName) : string
    {
        return PLUGIN_PATH . "templates/{$fileName}.php";
    }

    public static function stringReplace($string) : string
    {
        return ucwords(str_replace("_", " ", $string));
    }

    public static function daysOfMonth(int $month = 11, int $year = 2021) : array
    {
        $daysPerMonth = [];
        $getDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($i = 1; $i <= $getDays; $i++) {
            $daysPerMonth[] = $i;
        }
        return $daysPerMonth;
    }

    public static function getMonths() : array
    {
        $months = [];
        $currentMonth = date('m');
        for ($m = $currentMonth; $m < $currentMonth + 12; $m++) {
            $monthName = date('F', mktime(0, 0, 0, $m, 1));
            $months[$monthName] = date('n', mktime(0, 0, 0, $m, 1));
        }
        return $months;
    }

    public static function getDaysOfMonth()
    {
        $month = $_POST['month'] ?? null;
        $days = self::daysOfMonth($month, 2021);
        self::sendResponseDaysOfMonth($month, $days);
    }

    static function sendResponseDaysOfMonth($month, $days)
    {
        $posts = self::getPostsByMonth($month);
        ob_start();
        require self::getPath('days-of-month');
        $html = ob_get_contents();
        ob_end_clean();

        wp_send_json(['result' => $html]);
    }

    public static function getPostsByMonth() : array
    {
        $args = [
            'post_type' => 'newspapers',
            'meta_key'  => 'fx_event__date',
        ];

        return get_posts($args);
    }
}