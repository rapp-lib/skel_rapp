<?php
    require_once __DIR__."/../bootstrap.php";

    app_init('R\Lib\Core\Container\ConfigBasedApplication', array(
        "config" => include(constant("R_APP_ROOT_DIR")."/config/config.php"),
        "tags" => array("http","http-www"),
    ));
    try {
        $response = app()->router->execCurrentRoute();
    } catch (R\Lib\Core\Exception\ResponseException $e) {
        $response = $e->getResponse();
    }
    $response->render();
