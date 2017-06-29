<?php
    $app = new R\Lib\Core\Container\ConfigBasedApplication();
    $app->config(array(
        // Debug
        "debug.dev_cidr" => $app->env("DEBUG_DEV_CIDR", "0.0.0.0/0"),
        "debug.level" => $app->env("DEBUG_LEVEL", false),
        // DB
        'db.connection.default' => array(
            'driver' => 'mysql',
            'encoding' => 'utf8',
            'persistent' => false,
            'prefix' => '',
            'host' => $app->env("DB_DEFAULT_HOST", "127.0.0.1"),
            'database' => $app->env("DB_DEFAULT_DBNAME", "test"),
            'login' => $app->env("DB_DEFAULT_USER", "dev"),
            'password' => $app->env("DB_DEFAULT_PASS", "pass"),
        ),
        // Console
        "console.rexe.command" => array(
            "schema" => 'R\Lib\Console\Command\SchemaCommand',
            "build" => 'R\Lib\Console\Command\BuildCommand',
        ),
    ));
    $app->config(array(
        "http.webroots.www" => array(
            "base_uri"=>"",
            "middlewares"=>array(
                351 => function($request, $next) {
                    // StoredFileRequestIntercepter
                    $path = $request->getUri()->getPagePath();
                    if (preg_match('!^/file:!',$path)) {
                        $code = preg_replace('!^/file:/!','',$path);
                        return app()->response->downloadStoredFile(file_storage()->get($code));
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
    app_set($app);
    return $app;
