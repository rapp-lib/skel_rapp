<?php
    define("R_APP_PROVIDER","R\\App\\Application::getInstance");
    ini_set("display_errors",false);
    error_reporting(E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
    app()->config(array(
        "Report.debug_level" => false,
        "Report.auto_deploy" => false,
    ));
    require_once __DIR__."/routing.config.php";
    require_once __DIR__."/db.config.php";
    require_once __DIR__."/env.config.php";
