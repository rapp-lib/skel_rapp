#!/usr/bin/php
<?php

    require_once __DIR__."/../config/config.php";

    $params =get_cli_params();
    $statements = R\Util\Migration::getMigrateSQL($params["ds"]);
    foreach ($statements as $statement) {
        print $statement.";\n\n";
    }

    shutdown_webapp("normal");
