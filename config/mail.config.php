<?php
    return array(
        "mail"=>array(
            'from' => array(
                'address' => 'system@example.com',
                'name' => 'System',
            ),
            'admin_to' => array("system@example.com"),
            // Supported: "smtp", "sendmail", "mailgun", "mandrill", "ses",
            //            "sparkpost", "log", "array"
            'driver' => 'sendmail',
            'sendmail' => '/usr/sbin/sendmail -bs',
            'host' => 'smtp.mailgun.org',
            'port' => 587,
            'encryption' => 'tls',
            'username' => "anonymous",
            'password' => "password",
        ),
    );