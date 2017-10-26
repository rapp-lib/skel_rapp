<?php
    $mail->from("info@example.com");
    $mail->vars["admin_to"] = "admin@example.com";
    /*
    $mail->smtp(array(
        "auth" => true,
        "secure" => "tls",
        "host" => "example.com",
        "port" => 587,
        "username" => "info@example.com",
        "password" => "password",
    ));
    */
