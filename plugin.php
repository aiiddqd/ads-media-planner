<?php

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
