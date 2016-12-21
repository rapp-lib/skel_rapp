<?php
    require_once __DIR__."/../bootstrap.php";
    // Webroot設定
    webroot("www")->setAttrs(array(
        "domain_name" => $_SERVER["SERVER_NAME"],
        "is_secure" => false,
        "docroot_dir" => realpath(__DIR__),
        "webroot_url" => "",
    ));
    // リクエストURL設定
    route()->setCurrentRoute(webroot("www")->getRoute("url:".$_SERVER['REQUEST_URI']));
    // Assetの設定
    asset()->registerAssetsRoute(route("/.assets/lib/.assets.php"));
    asset()->registerAssetsRoute(route("/.assets/app/.assets.php"));
    // エラー時表示ファイルの設定
    app()->config(array("Config.error_document" =>array(
        "404" =>route("/.assets/errors/404.php")->getFile(),
        "500" =>route("/.assets/errors/500.php")->getFile(),
    )));
    app()->start("startWebapp","endWebapp");
