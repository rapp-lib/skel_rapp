<?php
    app()->config(array("DBI.connection.default" =>array(
        'driver' => 'mysql',
        'encoding' => 'utf8',
        'persistent' => true,
        'prefix' => '',
        'host' => 'localhost',
        'database' => 'test',
        'login' => 'dev',
        'password' => 'pass',
    )));
