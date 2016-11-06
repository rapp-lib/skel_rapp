<?php
    app()->config(array(
        "Path.webapp_dir" =>realpath(__DIR__."/.."),
        "Path.tmp_dir" =>realpath(__DIR__."/../tmp"),
        "Report.force_reporting" =>false,
        "Config.auto_deploy" =>false,
        "Config.dync_key" =>"_",
        // DB接続
        "DBI.connection" =>array(
            "default" =>array(
                'driver' => 'mysql',
                'encoding' => 'utf8',
                'persistent' => true,
                'prefix' => '',
                'host' => 'localhost',
                'database' => 'test',
                'login' => 'dev',
                'password' => 'pass',
            ),
        ),
    ));
