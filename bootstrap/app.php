<?php
    $app = new R\Lib\Core\AppContainer();
    $app->config(include(constant("R_APP_ROOT_DIR")."/config/storage.config.php"));
    $app->config(include(constant("R_APP_ROOT_DIR")."/config/webroot.config.php"));
    $app->config(include(constant("R_APP_ROOT_DIR")."/config/routing.config.php"));
    $app->config(include(constant("R_APP_ROOT_DIR")."/config/auth.config.php"));
    if ($app->env("APP_ENV")==="develop") {
        //$app->config->set('db.connection.default.database', "test_dev");
    }
    if ($app->env("APP_DEBUG")) {
        $app->debug->setDebugLevel(1);
    }
    return $app;
