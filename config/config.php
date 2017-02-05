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
            function () {
                return array(
                    // Debug
                    "debug.dev_cidr" => app()->env("DEBUG_DEV_CIDR", "0.0.0.0/0"),
                    "debug.level" => app()->env("DEBUG_LEVEL", false),
                    // DB
                    'db.connection.default' => array(
                        'driver' => 'mysql',
                        'encoding' => 'utf8',
                        'persistent' => false,
                        'prefix' => '',
                        'host' => app()->env("DB_DEFAULT_HOST", "localhost"),
                        'database' => app()->env("DB_DEFAULT_DBNAME", "test"),
                        'login' => app()->env("DB_DEFAULT_USER", "dev"),
                        'password' => app()->env("DB_DEFAULT_PASS", "pass"),
                    ),
                    // Webroot
                    "router.webroot.www.config" => array(
                        "domain_name" => app()->env("WEBROOT_WWW_DOMAIN", $_SERVER["SERVER_NAME"]),
                        "is_secure" => app()->env("WEBROOT_WWW_SECURE", $_SERVER["HTTPS"]),
                        "docroot_dir" => app()->env("WEBROOT_WWW_DOCROOT_DIR", $_SERVER["DOCUMENT_ROOT"]),
                        "webroot_url" => app()->env("WEBROOT_WWW_WEBROOT_URL", ""),
                    ),
                    "router.webroot.www.routing" => array(),
                );
            },
            include(__DIR__."/routing.config.php"),
        ),
        "config:http" => array(
            array(
                "router.middleware" => array(
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
            ),
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
        "config:console-rexe" => array(
            array(
                "debug.level" => true,
                "console.command" => array(
                    "schema" => 'R\Lib\Console\Command\SchemaCommand',
                    "build" => 'R\Lib\Console\Command\BuildCommand',
                ),
            ),
        ),
    );
