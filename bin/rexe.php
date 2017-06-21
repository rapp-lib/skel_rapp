<?php
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->error->listenPhpError();
    $app->log->listenPhpError();
    $app->log->registerReportHandler();
    $app->console->execCurrentCommand("rexe");
