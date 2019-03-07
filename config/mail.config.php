<?php
    return array(
        "mail"=>array(
            'from' => array(
                'address' => 'support_sp@toadkk.co.jp',
                'name' => 'support_sp@toadkk.co.jp',
            ),
            // 'admin_to' => array("nonaka+toadkk_admin@sharingseed.co.jp"),
            'admin_to' => array("yamamoto@highqualityandliteracy.com","support_sp@toadkk.co.jp"),
            // Supported: "smtp", "sendmail", "mailgun", "mandrill", "ses",
            //            "sparkpost", "log", "array"
            'driver' => 'smtp',
            'sendmail' => '/usr/sbin/sendmail -bs',
            'host' => 'mail.spcloud.jp',
            'port' => 465,
            'encryption' => 'SSL',
            'username' => "support_sp@toadkk.co.jp",
            'password' => "wkmmtk28",
        ),
    );
