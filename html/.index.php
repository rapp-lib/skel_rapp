<?php
    require_once __DIR__."/../bootstrap.php";
    try {
        ob_start();
        $_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__);
        app_init('R\Lib\Core\Container\ConfigBasedApplication', array(
            "config" => include(constant("R_APP_ROOT_DIR")."/config/config.php"),
            "tags" => array("http","http-www"),
        ));
        $response = app()->router->execCurrentRoute();
        $response->render();
    } catch (R\Lib\Core\Exception\ResponseException $e) {
        $response = $e->getResponse();
        $response->render();
    }
