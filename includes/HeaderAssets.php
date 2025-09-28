<?php

namespace AdsMediaPlanner;

HeaderAssets::init();

class HeaderAssets
{
    public static function init()
    {
        add_action('wp_head', [self::class, 'add_header_code']);
        add_action('admin_init', [self::class, 'register_settings'], 20);
    }

    public static function register_settings()
    {
        add_settings_field(
            id: 'header_code',
            title: 'Header Code',
            callback: function () {
                $value = Settings::get('header_code') ?? '';
                printf(
                    '<textarea name="%s" rows="10" cols="50" class="large-text code">%s</textarea>',
                    esc_attr(Settings::getFieldName('header_code')),
                    esc_textarea($value)
                );
                echo '<p class="description">Code to be added in the &lt;head&gt; section of the site.</p>';
            },
            page: Settings::$page_settings,
            section: Settings::$section_general
        );
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
        ?>
        <script type="text/javascript" id="ads-media-planner-header-code">
            <?= $header_code; ?>
        </script>
        <?php
    }
}
