<?php
    $app = new R\Lib\Core\Container\ConfigBasedApplication();
    $app->config(array(
        'db.connection.default' => array(
            'driver' => 'mysql',
            'encoding' => 'utf8',
            'persistent' => false,
            'prefix' => '',
            'host' => "127.0.0.1",
            'database' => "test",
            'login' => "dev",
            'password' => "pass",
        ),
    ));
    $app->config(array(
        "console.rexe.command" => array(
            "schema" => 'R\Lib\Console\Command\SchemaCommand',
            "build" => 'R\Lib\Console\Command\BuildCommand',
        ),
    ));
    $app->config(array(
        "http.webroots.www" => array(
            "base_uri" => "",
            "middlewares" => array(
                150 => function($request, $next) {
                    // PhpSessionStart
                    app()->session->start();
                    $response = $next($request);
                    app()->session->end();
                    return $response;
                },
                250 => function($request, $next) {
                    // StoredFileRequestInterceptor
                    $path = $request->getUri()->getPagePath();
                    if (preg_match('!^/file:!',$path)) {
                        $code = preg_replace('!^/file:/!','',$path);
                        return app()->file_storage->get($code)->getResponse();
                    }
                    return $next($request);
                },
                550 => function($request, $next) {
                    // PrivRequiredFirewall
                    $priv_required = $request->getUri()->getPageAction()->getController()->getPrivRequired();
                    if ($response = app()->auth()->requirePriv($priv_required)) {
                        return $response;
                    }
                    return $next($request);
                },
            ),
            "assets_catalog_uris"=>array(
                "path://.assets/lib/.assets.php",
                "path://.assets/app/.assets.php",
            ),
        ),
    ));
    $app->config(include(__DIR__."/routing.config.php"));
    if ($app->env("APP_ENV")==="develop") {
        //$app->debug->setDebugLevel(1);
        //$app->config->set('db.connection.default.database', "test_dev");
    }
    app_set($app);
    return $app;
