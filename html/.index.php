<?php
    ob_start();
    ini_set("display_errors", false);
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->report->listenPhpError();
    try {
        $response = $app->http->serve("www", function($request){
            return $request->getUri()->getPageAction()->run($request);
        });
    } catch (\Exception $e) {
        $app->report->logException($e);
        $response = $app->http->response("error");
    }
    $app->http->emit($response);
