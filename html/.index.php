<?php
    ini_set("display_errors", false);
    ob_start();
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->report->listenPhpError();
    if ($app->debug()) {
        $app->report->enableDebugReport();
    }
    try {
        $request = $app->http->serve("www");
        $response = $request->dispatch(function($request){
            $response = $request->getUri()->getPageAction()->run($request);
            return $response;
        });
        $app->http->emit($response);
    } catch (\Exception $e) {
        $response = $app->http->response("error");
        $app->http->emit($response);
        throw $e;
    }
