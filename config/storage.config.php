<?php
    return array(
        'db.connection.default' => array(
            'driver' => 'pdo_mysql',
            'charset' => 'utf8',
            'persistent' => false,
            'host' => "127.0.0.1",
            // 'dbname' => "ah113vaphr_toadkk",
            'dbname' => "ah113vaphr_toatest",
            'user' => "ah113vaphr",
            'password' => "mEiDFWFV",
        ),
        'cache.connection.default' => array(
            'adapter' => array(
                'name'    => 'apc',
                'options' => array('ttl' => 60*60*24*3),
            ),
            'plugins' => array(
                'Serializer',
                'exception_handler' => array('throw_exceptions' => false),
            ),
        ),
        'cache.connection.cred' => array(
            'adapter' => array(
                'name'    => 'filesystem',
                'options' => array(
                    'cache_dir' => constant("R_APP_ROOT_DIR")."/tmp/cred",
                    'dir_level' => 3,
                    'dir_permission' => 0775,
                    'file_permission' => 0664,
                    'ttl' => 7200,
                ),
            ),
            'plugins' => array(
                'Serializer',
                'exception_handler' => array('throw_exceptions' => false),
            ),
        ),
        'session' => array(
            'auto_start' => true,
            'config.options' => array(),
            //'save_handler.cache' => "session",
        ),
        "file.storages" => array(
            "public" => array(
                "uri_parser" => function($storage, $uri) {
                    try {
                        $params = app()->http->webroot("www")->uri($uri)->getEmbedParams();
                        return $params["storage"] == $storage->getName() ? $params : false;
                    } catch (\InvalidArgumentException $e) {
                        return false;
                    }
                },
                "params_filter" => function($storage, $params) {
                    $params["tmp_dir"] = constant("R_APP_ROOT_DIR")."/tmp";
                    $params["base_uri"] = app()->http->webroot("www")->getBaseUri()->withoutAuthority();
                    $params["rand"] = md5(mt_rand());
                    $params["date"] = date("Y/m/d");
                    return $params;
                },
                "id" => "{date}/{rand}/{filename}",
                "source" => "{tmp_dir}/file/{storage}/{id}",
                "uri" => "{base_uri}/.file/{storage}/{id}",
            ),
            "tmp" => array(
                "uri_parser" => function($storage, $uri) {
                    if (preg_match('!^file://([^/]+)/(.+)$!', $uri, $_) && $_[1]===$storage->getName()) {
                        return array("id"=>$_[2]);
                    }
                    return false;
                },
                "params_filter" => function($storage, $params) {
                    $params["tmp_dir"] = constant("R_APP_ROOT_DIR")."/tmp";
                    $params["rand"] = md5(mt_rand());
                    $params["session_id"] = app()->session->getId();
                    return $params;
                },
                "id" => "{rand}/{filename}",
                "source" => "{tmp_dir}/file/{storage}/{session_id}/{id}",
                "uri" => "file://{storage}/{id}",
            ),
        ),
    );
