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
 * Version: 0.2.250928
 */

namespace AdsMediaPlanner;

Plugin::init();

class Plugin {

    public static function init()
    {
        foreach (glob(__DIR__ . "/includes/*.php") as $filename) {
            include_once $filename;
        }
    }
}
