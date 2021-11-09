<?php

namespace Includes;

class MainSettings
{
    public static function init()
    {
        add_action('admin_menu', [self::class, 'admin_options_page']);
        add_action('admin_init', [self::class, 'admin_settings_init']);
    }

    static function admin_options_page()
    {
        add_menu_page(
            __('FX Events', 'fxevents'),
            __('FX Events', 'fxevents'),
            'manage_options',
            'fx_events_settings_page',
            [self::class, 'create_admin_page'],
            'dashicons-calendar-alt',
            20
        );
    }

    static function create_admin_page()
    {
        require_once Functions::getPath('admin/admin-page');
    }

    static function admin_settings_init()
    {
        register_setting('fx_event_setting', 'fx_events_setting_option');

        add_settings_section(
            'fx_event_setting_section',
            __('Events Settings', 'fxevents'),
            [self::class, 'fx_event_section_body'],
            'fx_events_settings_page'
        );

        self::addFields();
    }

    static function fx_event_section_body()
    {
        echo 'Title';
    }

    static function addFields()
    {
        self::registerField('posts_per_page');
    }

    static function registerField($slug)
    {
        add_settings_field(
            'fx_event_' . $slug,
            'Posts per page',
            [self::class, 'addField'],
            'fx_events_settings_page',
            'fx_event_setting_section',
        );

        self::addField('fx_event_' . $slug);
    }

    static function addField($name)
    {
        $field = get_option('fx_events_setting_option');
        ?>
        <input type="text"
               name="fx_events_setting_option[<?php echo $name ?>]"
               id="<?php echo $name ?>"
               value="<?php echo esc_attr__($field[$name]) ?>"
        >
        <?php
    }
}