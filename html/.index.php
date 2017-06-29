<?php
    require_once __DIR__."/../bootstrap.php";

    $app = include(constant("R_APP_ROOT_DIR")."/config/app.php");
    $app->log->listenPhpError();
    $app->error->listenPhpError();
    if ($app->debug()) {
        $app->log->registerReportHandler();
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
