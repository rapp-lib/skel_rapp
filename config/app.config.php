<?php
    return array(
        "app"=>array(
            'env' => app()->env('APP_ENV'),
            'debug' => app()->env('APP_DEBUG', false),
            'key' => app()->env('APP_KEY'),
            'timezone' => 'Asia/Tokyo',
            'cipher' => constant("MCRYPT_RIJNDAEL_128"),
            'log' => 'daily',
            'providers' => array(
            ),
            'aliases' => array(
            ),
            'commands' => array(
            ),
        ),
    );
