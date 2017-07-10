<?php
    return array(
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
        'cache.connection.tmpfile' => array(
            'adapter' => array(
                'name'    => 'filesystem',
                'options' => array(
                    'namespace' => 'tmpfile',
                    'cache_dir' => constant("R_APP_ROOT_DIR")."/tmp/cache/tmpfile",
                    'dir_level' => 3,
                    'dir_permission' => 0775,
                    'file_permission' => 0664,
                ),
            ),
            'plugins' => array(
                'exception_handler' => array('throw_exceptions' => false),
                'Serializer',
            ),
        ),
        'session.manager.default' => array(
            'storage.class' => 'Zend\Session\Storage\SessionArrayStorage',
            'config.class' => 'Zend\Session\Config\SessionConfig',
            'config.options' => array(
            ),
        ),
        "file.storages" => array(
            "public" => array(
                "uri_parser" => function($storage, $uri) {
                    $params = app()->http->webroot("www")->uri($uri)->getEmbedParams();
                    return $params["storage"] == $storage->getName() ? $params : false;
                },
                "params_filter" => function($storage, $params) {
                    $params["tmp_dir"] = constant("R_APP_ROOT_DIR")."/tmp";
                    $params["base_uri"] = app()->http->webroot("www")->getBaseUri();
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