<?php
    ob_start();
    ini_set("display_errors", 1);
    require_once __DIR__."/../bootstrap/autoload.php";

    $app = include(constant("R_APP_ROOT_DIR")."/bootstrap/app.php");
    try {
        $response = $app->http->serve("www", function($request){
            return $request->getUri()->getPageController()->run($request);
        });
    } catch (\Exception $e) {
        $app->report->logException($e);
        $response = $app->http->response("error");
    }
    $app->http->emit($response);
