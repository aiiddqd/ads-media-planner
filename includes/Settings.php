<?php

namespace AdsMediaPlanner;

Settings::init();

class Settings
{

    public static $option_group = 'ads_media_planner_options';

    public static $page_settings = 'ads_media_planner';

    public static $section_general = 'general';

    public static $option_name = 'ads_media_planner_config';

    public static function init()
    {
        add_action('admin_menu', [self::class, 'add_options_page']);
        //admin_init
        add_action('admin_init', [self::class, 'addFields']);
    }

    public static function get($key = null)
    {
        $options = get_option(self::$option_name, []);
        return $key ? ($options[$key] ?? null) : $options;
    }

    public static function set($key, $value)
    {
        $options = get_option(self::$option_name, []);
        $options[$key] = $value;
        update_option(self::$option_name, $options);
    }

    public static function getFieldName($key)
    {
        return self::$option_name."[$key]";
    }

    public static function isEnabled()
    {
        return self::get('enable') ?? false;
    }

    public static function addFields()
    {

        register_setting(self::$option_group, self::$option_name);

        add_settings_section(
            self::$section_general,
            'General',
            function () {
                echo '<p>Configure the Ads Media Planner settings below.</p>';
            },
            self::$page_settings
        );

        add_settings_field(
            'enable',
            'Enable',
            function ($args) {

                $value = self::get('enable');
                printf(
                    '<input type="checkbox" name="%s" value="1" %s>',
                    esc_attr(self::getFieldName('enable')),
                    checked($value, 1, false)
                );
                ?>

            <?php
            },
            self::$page_settings,
            self::$section_general
        );

        //отключить рекламу для авторизованных посетителей
        add_settings_field(
            'disable_for_logged_in',
            'Disable for logged-in users',
            function ($args) {

                $value = self::get('disable_for_logged_in');
                printf(
                    '<input type="checkbox" name="%s" value="1" %s>',
                    esc_attr(self::getFieldName('disable_for_logged_in')),
                    checked($value, 1, false)
                );
                ?>

            <?php
            },
            self::$page_settings,
            self::$section_general
        );
    }

    public static function add_options_page()
    {
        add_submenu_page(
            'edit.php?post_type=ad-block',
            'Ads Media Planner',
            'Settings',
            'manage_options',
            'ads-media-planner-settings',
            [self::class, 'render_options_page']
        );
    }

    public static function render_options_page()
    {
        ?>
        <div class="wrap">
            <h1>Ads Media Planner Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields(self::$option_group);
                do_settings_sections(self::$page_settings);
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
