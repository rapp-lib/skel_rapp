<?php
    ini_set("display_errors",false);
    error_reporting(E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
    require_once __DIR__."/vendor/autoload.php";
    require_once __DIR__."/config/base.config.php";
    require_once __DIR__."/config/routing.config.php";
    require_once __DIR__."/config/schema.config.php";
    require_once __DIR__."/config/env.config.php";
    app()->init();
