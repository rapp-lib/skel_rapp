<?php

    registry(array(
        "Routing.page_to_path" =>array(

            // トップページ
            "index.index" =>"/index/index.html",

            // 会員登録フォーム
            "customer_entry.index" =>"/customer_entry/index.html",
            "customer_entry.entry_form" =>"/customer_entry/entry_form.html",
            "customer_entry.entry_confirm" =>"/customer_entry/entry_confirm.html",
            "customer_entry.entry_exec" =>"/customer_entry/entry_exec.html",

            // 会員ログイン
            "customer_login.index" =>"/customer_login/index.html",
            "customer_login.login" =>"/customer_login/login.html",
            "customer_login.logout" =>"/customer_login/logout.html",

            // 会員マイページ
            "customer_mypage.index" =>"/customer_mypage/index.html",

            // 管理者ログイン
            "admin_login.index" =>"/admin_login/index.html",
            "admin_login.login" =>"/admin_login/login.html",
            "admin_login.logout" =>"/admin_login/logout.html",

            // 製品管理
            "product_master.index" =>"/product_master/index.html",
            "product_master.view_list" =>"/product_master/view_list.html",
            "product_master.entry_form" =>"/product_master/entry_form.html",
            "product_master.entry_confirm" =>"/product_master/entry_confirm.html",
            "product_master.entry_exec" =>"/product_master/entry_exec.html",
            "product_master.delete" =>"/product_master/delete.html",
            "product_master.view_csv" =>"/product_master/view_csv.html",
            "product_master.entry_csv_form" =>"/product_master/entry_csv_form.html",
            "product_master.entry_csv_confirm" =>"/product_master/entry_csv_confirm.html",
            "product_master.entry_csv_exec" =>"/product_master/entry_csv_exec.html",

            // ユーザ管理
            "user_master.index" =>"/user_master/index.html",
            "user_master.view_list" =>"/user_master/view_list.html",
            "user_master.entry_form" =>"/user_master/entry_form.html",
            "user_master.entry_confirm" =>"/user_master/entry_confirm.html",
            "user_master.entry_exec" =>"/user_master/entry_exec.html",
            "user_master.delete" =>"/user_master/delete.html",
        ),
    ));
