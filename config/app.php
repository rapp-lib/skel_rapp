<?php
    $app = new R\Lib\Core\AppContainer();
    $app->config(include(__DIR__."/storage.config.php"));
    $app->config(include(__DIR__."/webroot.config.php"));
    $app->config(include(__DIR__."/routing.config.php"));
    $app->config(include(__DIR__."/auth.config.php"));
    if ($app->env("APP_ENV")==="develop") {
        //$app->debug->setDebugLevel(1);
        //$app->config->set('db.connection.default.database', "test_dev");
    }
    app_set($app);
    return $app;
