#!/usr/bin/php
<?php
    require_once __DIR__."/../bootstrap.php";
    app()->config(array("Config.debug_level"=>true));
    $params =get_cli_params();

    foreach (util("Migration")->getMigrateSQL($params["ds"]) as $statement) {
        print $statement.";\n\n";
    }
    shutdown_webapp("normal");
