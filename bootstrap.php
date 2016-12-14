<?php
    ini_set("display_errors",true);
    error_reporting(E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
    require_once __DIR__."/vendor/autoload.php";
    require_once __DIR__."/config/base.config.php";
    require_once __DIR__."/config/routing.config.php";
    if (file_exists(__DIR__."/config/env.config.php")) {
        require_once __DIR__."/config/env.config.php";
    }
    app()->init();
