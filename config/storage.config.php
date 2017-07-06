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
    );