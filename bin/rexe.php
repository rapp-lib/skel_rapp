<?php
    ini_set("display_errors", false);
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->report->listenPhpError();
    $app->debug->setDebugLevel(1);
    $app->console->execCurrentCommand("rexe");
