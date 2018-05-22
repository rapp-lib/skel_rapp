<?php
    ob_start();
    ini_set("display_errors", false);
    require_once __DIR__."/../bootstrap/autoload.php";
    $app = include(constant("R_APP_ROOT_DIR")."/bootstrap/app.php");
    $response = $app->http->serve("www", function($request){
        return $request->getUri()->getPageController()->run($request);
    });
    $app->http->emit($response);
