<?php
    return array(
        "bind" => array(
            "middleware" => array(
            ),
            "provider" => array(
            ),
            "contract" => array(
            ),
        ),
        "config" => array(
            array(
                "debug.dev_cidr" => "0.0.0.0/0",
                "debug.level" => 1,
                "builder.overwrite" => false,
            ),
            array(
                "router.webroot.www.config" => array(
                    "domain_name" => $_SERVER["SERVER_NAME"],
                    "is_secure" => $_SERVER["HTTPS"],
                    "docroot_dir" => $_SERVER["DOCUMENT_ROOT"],
                    "webroot_url" => "",
                ),
                "router.webroot.www.routing" => array(),
            ),
            include(__DIR__."/routing.config.php"),
            include(__DIR__."/db.config.php"),
            include(__DIR__."/env.config.php"),
        ),
        "config:http-www" => array(
            array(
                "router.current_webroot" => "www",
                "router.current_url" => $_SERVER['REQUEST_URI'],
                "asset.catalogs" => array(
                    "/.assets/lib/.assets.php",
                    "/.assets/app/.assets.php",
                ),
            ),
        ),
        "config:http" => array(
            array(
                "controller.middleware" => array(
                    "auth" => function () {
                        return true;
                    },
                    "stored_file_service" => function () {
                        $path = app()->router->getCurrentRoute()->getPath();
                        return preg_match('!^/file:!',$path);
                    },
                    "json_response_fallback" => function () {
                        $file = app()->router->getCurrentRoute()->getFile();
                        return preg_match('!\.json$!',$file);
                    },
                    "view_response_fallback" => function () {
                        $file = app()->router->getCurrentRoute()->getFile();
                        return preg_match('!(\.html|/)$!',$file);
                    },
                ),
                "app.exec" => function () {
                    $route = app()->router->getCurrentRoute();
                    $response = $route->getController()->exec();
                    return $response;
                },
            ),
        ),
    );
