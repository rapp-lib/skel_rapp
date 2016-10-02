<?php

    registry(array(
        "Routing.page_to_path" =>array(

            // トップページ
            "index.index" => "/index.html",

            // 会員登録
            "m_register.index" => "/m/register/index.html",
            "m_register.entry_form" => "/m/register/entry_form.html",
            "m_register.entry_confirm" => "/m/register/entry_confirm.html",
            "m_register.entry_exec" => "/m/register/entry_exec.html",

            // 会員ログイン
            "m_login.index" => "/m/login/index.html",
            "m_login.login" => "/m/login/login.html",
            "m_login.logout" => "/m/login/logout.html",

            // 会員トップ
            "m_index.index" => "/m/index.html",

            // 管理者ログイン
            "a_login.index" => "/a/login/index.html",
            "a_login.login" => "/a/login/login.html",
            "a_login.logout" => "/a/login/logout.html",

            // 製品管理
            "a_master_product.index" => "/a/master/product/index.html",
            "a_master_product.view_list" => "/a/master/product/view_list.html",
            "a_master_product.entry_form" => "/a/master/product/entry_form.html",
            "a_master_product.entry_confirm" => "/a/master/product/entry_confirm.html",
            "a_master_product.entry_exec" => "/a/master/product/entry_exec.html",
            "a_master_product.entry_csv_form" => "/a/master/product/entry_csv_form.html",
            "a_master_product.entry_csv_confirm" => "/a/master/product/entry_csv_confirm.html",
            "a_master_product.entry_csv_exec" => "/a/master/product/entry_csv_exec.html",
            "a_master_product.view_csv" => "/a/master/product/view_csv.html",

            // 会員管理
            "a_master_member.index" => "/a/master/member/index.html",
            "a_master_member.view_list" => "/a/master/member/view_list.html",
            "a_master_member.entry_form" => "/a/master/member/entry_form.html",
            "a_master_member.entry_confirm" => "/a/master/member/entry_confirm.html",
            "a_master_member.entry_exec" => "/a/master/member/entry_exec.html",
        ),
    ));
