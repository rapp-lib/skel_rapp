<?php
    require_once __DIR__."/vendor/autoload.php";
    define("R_APP_ROOT_DIR", __DIR__);
    define("R_APP_ENV_FILE", __DIR__."/.env");
    ini_set("display_errors", true);
    error_reporting(E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
