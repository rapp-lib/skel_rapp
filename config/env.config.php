<?php
    $env = new Dotenv\Dotenv(__DIR__."/..");
    $env->load();
    app()->config(array(
    ));