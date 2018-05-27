<?php
    ob_start();
    ini_set("display_errors", false);
    $_ENV["APP_WEBROOT"] = "www";
    require_once __DIR__."/../bootstrap/autoload.php";
    $app = include(constant("R_APP_ROOT_DIR")."/bootstrap/app.php");
    $app->runHttp();
