<?php
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->error->listenPhpError();
    $app->log->listenPhpError();
    if ($app->debug()) {
        $app->log->registerReportHandler();
    }
    $app->error->onError(function($message, $params, $error_options)use($app){
        $response = $app->response->error("", 500);
        $app->response->respond($response);
    });
    $app->router->setCurrentRoute("www", $_SERVER['REQUEST_URI']);
    $response = $app->router->execCurrentRoute();
    $app->response->respond($response);
