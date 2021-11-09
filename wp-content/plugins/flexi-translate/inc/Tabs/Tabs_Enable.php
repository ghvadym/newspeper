<?php
/**
 * Created by PhpStorm.
 * User: bvn-564
 * Date: 6/10/21
 * Time: 11:57 AM
 */

namespace Tabs;


class Tabs_Enable
{
    public function __construct()
    {
        add_action('admin_menu', [$this,'sd_admin_settings_setup']);
    }

    function sd_admin_settings_setup()
    {
        add_options_page('Flexi Translate', 'Flexi Translate', 'manage_options', 'flexi-translate', [$this, 'sd_admin_settings_page']);
    }


    function sd_admin_settings_page()
    {
        do_action('flexi_content');
    }

}