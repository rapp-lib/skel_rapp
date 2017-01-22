<?php
    require_once __DIR__."/../bootstrap.php";
    app()->router()->getWebroot("www")->setConfig(array(
        "domain_name" => $_SERVER["SERVER_NAME"],
        "is_secure" => false,
        "docroot_dir" => realpath(__DIR__),
        "webroot_url" => "",
    ));
    app()->router()->setCurrent("www", "url:".$_SERVER['REQUEST_URI']);
    app()->asset()->loadAssetCatalog(route("/.assets/lib/.assets.php"));
    app()->asset()->loadAssetCatalog(route("/.assets/app/.assets.php"));
    app()->config(array("Config.error_document" =>array(
        "404" => route("/.assets/errors/404.php")->getFile(),
        "500" => route("/.assets/errors/500.php")->getFile(),
    )));
    $response = app()->router()->invokeCurrentRoute();
    $response->raise();
