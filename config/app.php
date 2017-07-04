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
        'cache.connection.default' => array(
            'adapter' => array(
                'name'    => 'apc',
                'options' => array('ttl' => 3600),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
            ),
        ),
        'session.manager.default' => array(
            'storage.class' => 'Zend\Session\Storage\SessionArrayStorage',
            'config.class' => 'Zend\Session\Config\SessionConfig',
            'config.options' => array(
            ),
        ),
    ));
    $app->config(array(
        "http.webroots.www" => array(
            "base_uri" => "",
            "middlewares" => array(
                150 => function($request, $next) {
                    // SessionStart
                    app()->session->start();
                    return $next($request);
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
