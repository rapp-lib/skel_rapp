<?php

    registry(array(
        "Routing.page_to_path" =>array(

            // トップページ
            "index.index" => "/index.html",

            // 会員登録
            "member_register.index" => "/member/register/index.html",
            "member_register.entry_form" => "/member/register/entry_form.html",
            "member_register.entry_confirm" => "/member/register/entry_confirm.html",
            "member_register.entry_exec" => "/member/register/entry_exec.html",

            // 会員ログイン
            "member.index" => "/member/index.html",
            "member.login" => "/member/login.html",
            "member.logout" => "/member/logout.html",

            // 会員トップ
            "member_index.index" => "/member/index.html",

            // 管理者ログイン
            "admin.index" => "/admin/index.html",
            "admin.login" => "/admin/login.html",
            "admin.logout" => "/admin/logout.html",

            // 製品管理
            "admin_product_master.index" => "/admin/product/master/index.html",
            "admin_product_master.view_list" => "/admin/product/master/view_list.html",
            "admin_product_master.entry_form" => "/admin/product/master/entry_form.html",
            "admin_product_master.entry_confirm" => "/admin/product/master/entry_confirm.html",
            "admin_product_master.entry_exec" => "/admin/product/master/entry_exec.html",
            "admin_product_master.entry_csv_form" => "/admin/product/master/entry_csv_form.html",
            "admin_product_master.entry_csv_confirm" => "/admin/product/master/entry_csv_confirm.html",
            "admin_product_master.entry_csv_exec" => "/admin/product/master/entry_csv_exec.html",
            "admin_product_master.view_csv" => "/admin/product/master/view_csv.html",

            // 会員管理
            "admin_member_master.index" => "/admin/member/master/index.html",
            "admin_member_master.view_list" => "/admin/member/master/view_list.html",
            "admin_member_master.entry_form" => "/admin/member/master/entry_form.html",
            "admin_member_master.entry_confirm" => "/admin/member/master/entry_confirm.html",
            "admin_member_master.entry_exec" => "/admin/member/master/entry_exec.html",
        ),
    ));
