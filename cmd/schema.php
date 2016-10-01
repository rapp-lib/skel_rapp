<?php

use R\Util\Migration;

    require_once __DIR__."/../config/config.php";

    $params =get_cli_params();
    $ds_name = $params["ds"] ? $params["ds"] : "default";
    $tables = Migration::searchTableInDir(__DIR__."/../app/Table");
    $sql = Migration::getMigrateSQL($ds_name, $tables);
    print $sql;

    shutdown_webapp("normal");
