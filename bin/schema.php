#!/usr/bin/php
<?php

    require_once __DIR__."/../config/config.php";

    $params =get_cli_params();
    $ds_name = $params["ds"] ? $params["ds"] : "default";
    $statements = R\Util\Migration::getMigrateSQL($ds_name);
    foreach ($statements as $statement) {
        print $statement.";\n\n";
    }

    shutdown_webapp("normal");
