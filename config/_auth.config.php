<?php

    registry(array(

        // customer認証
        "Auth.customer" =>array(
            "context_name" =>"customer_auth",
            "force_login.redirect_to" =>"page:customer_login.login",
            "force_login.zone" =>array(
                "page:customer_mypage.*",
            ),
        ),

        // admin認証
        "Auth.admin" =>array(
            "context_name" =>"admin_auth",
            "force_login.redirect_to" =>"page:admin_login.login",
            "force_login.zone" =>array(
                "page:product_master.*",
                "page:user_master.*",
            ),
        ),
    ));
