#!/usr/bin/php
<?php

use R\Util\Migration;

    require_once __DIR__."/../config/config.php";

    $params =get_cli_params();
    $ds_name = $params["ds"] ? $params["ds"] : "default";
    $sql = Migration::getMigrateSQL($ds_name);
    print implode("\n",$sql)."\n";

    shutdown_webapp("normal");
