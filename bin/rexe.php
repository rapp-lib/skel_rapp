<?php
    ini_set("display_errors", false);
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->report->listenPhpError();
    $app->report->enableDebugReport();
    $app->console->execCurrentCommand("rexe");
