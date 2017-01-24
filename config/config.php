<?php
    return array(
        "bind" => array(
            "util.*" => function ($parts) {
                return 'R\App\Util\\'.$parts[1];
            },
            "util" => array(
                "File" => 'R\Lib\Core\Util\File',
            ),
            "middleware" => array(
              "auth" => 'R\Lib\Core\Middleware\Auth',
            ),
            "provider" => array(
                "router" => 'R\Lib\Route\RouteManager',
                "route" => 'R\Lib\Route\RouteManager',
                "config" => 'R\Lib\Core\Provider\Configure',
                "env" => 'R\Lib\Core\Provider\Env',
                "table" => 'R\Lib\Table\TableFactory',
                "form" => 'R\Lib\Form\FormFactory',
                "enum" => 'R\Lib\Enum\EnumFactory',
                "file_storage" => 'R\Lib\FileStorage\FileStorageManager',
                "builder" => 'R\Lib\Builder\WebappBuilder',
                "util" => 'R\Lib\Core\Provider\UtilProxyManager',
                "extension" => 'R\Lib\Core\Provider\ExtentionManager',
                "debug" => 'R\Lib\Core\Provider\DebugDriver',
                "auth" => 'R\Lib\Auth\AccountManager',
                "asset" => 'R\Lib\Asset\AssetManager',
            ),
            "response" => array(
                "error" => 'R\Lib\Core\Response\HttpResponse',
                "redirect" => 'R\Lib\Core\Response\HttpResponse',
                "view" => 'R\Lib\Core\Response\HttpResponse',
                "json" => 'R\Lib\Core\Response\HttpResponse',
                "download" => 'R\Lib\Core\Response\HttpResponse',
                "unknown" => 'R\Lib\Core\Response\HttpResponse',
            ),
        ),
        "bind:http" => array(
            "provider" => array(
                "report" => 'R\Lib\Core\Provider\ReportDriver',
                "response" => 'R\Lib\Core\Provider\ResponseFactory',
                "request" => 'R\Lib\Core\Provider\Request',
                "session" => 'R\Lib\Core\Provider\Session',
            ),
        ),
        "bind:console" => array(
            "provider" => array(
                "report" => 'R\Lib\Core\Provider\ReportDriver',
                "response" => 'R\Lib\Core\Provider\ResponseFactory',
                "request" => 'R\Lib\Core\Provider\Request',
            ),
        ),
        "config" => array(
            array(
                "debug.dev_cidr" => "0.0.0.0/0",
                "debug.level" => 1,
                "builder.overwrite" => false,
                "router.webroot.www.config" => array(
                    "domain_name" => null,
                    "is_secure" => false,
                    "docroot_dir" => null,
                    "webroot_url" => "",
                ),
                "router.webroot.www.routing" => array(),
            ),
            include(__DIR__."/db.config.php"),
            include(__DIR__."/routing.config.php"),
            include(__DIR__."/env.config.php"),
        ),
        "config:http-www" => array(
            array(
                "asset.catalogs" => array(
                    "/.assets/lib/.assets.php",
                    "/.assets/app/.assets.php",
                ),
                "app.exec" => function ($request) {
                    app()->router->setCurrent("www", "url:".$_SERVER['REQUEST_URI']);
                    return app()->request->invokeCurrentRoute();
                },
            ),
        ),
    );
