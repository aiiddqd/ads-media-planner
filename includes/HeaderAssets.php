<?php

namespace AdsMediaPlanner;

HeaderAssets::init();

class HeaderAssets
{
    public static function init()
    {
        add_action('wp_head', [self::class, 'add_header_code']);
        add_action('wp_body_open', [self::class, 'add_body_open_code']);
        add_action('admin_init', [self::class, 'add_settings'], 20);
    }

    public static function add_settings()
    {
        add_settings_field(
            'header_code',
            'Header Code',
            function () {
                $value = Settings::get('header_code') ?? '';
                printf(
                    '<textarea name="%s" rows="10" cols="50" class="large-text code">%s</textarea>',
                    esc_attr(Settings::getFieldName('header_code')),
                    esc_textarea($value)
                );
                echo '<p class="description">Code to be added in the &lt;head&gt; section of the site.</p>';
            },
            Settings::$page_settings,
            Settings::$section_general
        );

        add_settings_field(
            'body_open_code',
            'Body Open Code',
            function () {
                $value = Settings::get('body_open_code') ?? '';
                printf(
                    '<textarea name="%s" rows="10" cols="50" class="large-text code">%s</textarea>',
                    esc_attr(Settings::getFieldName('body_open_code')),
                    esc_textarea($value)
                );
                echo '<p class="description">Code to be added immediately after the opening &lt;body&gt; tag.</p>';
            },
            Settings::$page_settings,
            Settings::$section_general
        );
    }

    public static function add_body_open_code(){
        if (! Settings::isEnabled()) {
            return;
        }

        $body_open_code = Settings::get('body_open_code');
        if (empty($body_open_code)) {
            return;
        }
        echo $body_open_code;
    }

    public static function add_header_code()
    {
        if (! Settings::isEnabled()) {
            return;
        }

        $header_code = Settings::get('header_code');
        if (empty($header_code)) {
            return;
        }

        echo $header_code;
    }
}
