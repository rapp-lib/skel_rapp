<?php
    require_once __DIR__."/../vendor/autoload.php";

    define("R_APP_ROOT_DIR", realpath(__DIR__."/.."));

    ini_set("error_reporting", E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED);
    ini_set("date.timezone", "Asia/Tokyo");
    ini_set("mbstring.encoding_translation", false);
    ini_set("mbstring.internal_encoding", "UTF-8");
    ini_set("auto_detect_line_endings", true);
    ini_set("session.use_trans_sid", false);
    ini_set("session.gc_maxlifetime", 86400);
    ini_set("session.cookie_lifetime", 86400);
