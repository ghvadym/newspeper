<?php

namespace inc;

class SettingsTab
{
    public function __construct()
    {
        add_action('admin_menu', [$this,'pageSetup']);
    }

    public function pageSetup()
    {
        add_options_page('Flexi Form Settings', 'Flexi Form Settings', 'manage_options', 'flexi_form_settings', [$this, 'pageContent']);
    }
    
    public function pageContent()
    {
        include PLUGIN_DIR . 'templates/settings_page.php';
    }
}
