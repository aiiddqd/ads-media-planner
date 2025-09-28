<?php
/**
 * Plugin Name: Ads Media Planner
 * Description: A plugin to manage and plan media advertisements effectively.
 * Plugin URI: https://github.com/aiiddqd/ads-media-planner
 * Author: Antony I
 * Author URI: https://github.com/aiiddqd/
 * License: GPL2
 * Domain Path: /languages
 * Text Domain: ads-media-planner
 * Version: 0.3.250928
 */

namespace AdsMediaPlanner;

Plugin::init();

class Plugin {

    public static function init()
    {
        foreach (glob(__DIR__ . "/includes/*.php") as $filename) {
            include_once $filename;
        }

        add_filter('plugin_action_links_' . plugin_basename(__FILE__), [self::class, 'add_settings_link']);
    }

    // Add settings link to plugins page
    public static function add_settings_link($links) {
        $link = admin_url('edit.php?post_type=ad-block&page=ads-media-planner-settings');
        $settings_link = sprintf('<a href="%s">Settings</a>', esc_url($link));
        array_unshift($links, $settings_link);
        return $links;
    }
}
