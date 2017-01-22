<?php
    if (file_exists(__DIR__."/../.env")) {
        $env = new Dotenv\Dotenv(__DIR__."/..");
        $env->load();
    }
    app()->config(array(
    ));