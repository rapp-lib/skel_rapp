<?php
    webroot("www")->addRouting(array(
        "include.static" => "/include/*",
        "service.file" => "/file:/*",

        // トップページ
        "index.index" => "/index.html",
        "index.static" => "/*",

        // 記事表示
        "posts.index" => "/posts/index.html",
        "posts.view_list" => "/posts/view_list.html",
        "posts.view_detail" => "/posts/view_detail.html",
        "posts.rank_view_list" => "/posts/rank_view_list.html",

        // 問い合わせフォーム
        "contact.index" => "/contact/index.html",
        "contact.entry_form" => "/contact/entry_form.html",
        "contact.entry_confirm" => "/contact/entry_confirm.html",
        "contact.entry_exec" => "/contact/entry_exec.html",

        // 会員登録
        "member_register.index" => "/member/register/index.html",
        "member_register.entry_form" => "/member/register/entry_form.html",
        "member_register.entry_confirm" => "/member/register/entry_confirm.html",
        "member_register.entry_exec" => "/member/register/entry_exec.html",

        // 会員ログイン
        "member_login.index" => "/member/login/index.html",
        "member_login.login" => "/member/login/login.html",
        "member_login.logout" => "/member/login/logout.html",

        // 会員トップ
        "member_index.index" => "/member/index.html",
        "member_index.static" => "/member/*",

        // 会員情報変更
        "member_edit.index" => "/member/edit/index.html",
        "member_edit.entry_form" => "/member/edit/entry_form.html",
        "member_edit.entry_confirm" => "/member/edit/entry_confirm.html",
        "member_edit.entry_exec" => "/member/edit/entry_exec.html",

        // お気に入り変更
        "member_favolite_posts.index" => "/member/favolite/posts/index.html",
        // "member_favolite_posts.entry_form" => "/member/favolite/posts/entry_form.html",
        // "member_favolite_posts.entry_confirm" => "/member/favolite/posts/entry_confirm.html",
        // "member_favolite_posts.entry_exec" => "/member/favolite/posts/entry_exec.html",
        "member_favolite_posts.view_list" => "/member/favolite/posts/view_list.html",
        "member_favolite_posts.add_favolite" => "/member/favolite/posts/add_favolite.json",
        "member_favolite_posts.delete" => "/member/favolite/posts/delete.html",

        // 管理者ログイン
        "admin_login.index" => "/admin/login/index.html",
        "admin_login.login" => "/admin/login/login.html",
        "admin_login.logout" => "/admin/login/logout.html",

        // 管理者トップ
        "admin_index.index" => "/admin/index.html",
        "admin_index.static" => "/admin/*",

        // 記事管理
        "admin_post_master.index" => "/admin/post/master/index.html",
        "admin_post_master.view_list" => "/admin/post/master/view_list.html",
        "admin_post_master.entry_form" => "/admin/post/master/entry_form.html",
        "admin_post_master.entry_confirm" => "/admin/post/master/entry_confirm.html",
        "admin_post_master.entry_exec" => "/admin/post/master/entry_exec.html",
        "admin_post_master.delete" => "/admin/post/master/delete.html",
        "admin_post_master.view_csv" => "/admin/post/master/view_csv.html",
        "admin_post_master.entry_csv_form" => "/admin/post/master/entry_csv_form.html",
        "admin_post_master.entry_csv_confirm" => "/admin/post/master/entry_csv_confirm.html",
        "admin_post_master.entry_csv_exec" => "/admin/post/master/entry_csv_exec.html",

        // 会員管理
        "admin_member_master.index" => "/admin/member/master/index.html",
        "admin_member_master.view_list" => "/admin/member/master/view_list.html",
        "admin_member_master.entry_form" => "/admin/member/master/entry_form.html",
        "admin_member_master.entry_confirm" => "/admin/member/master/entry_confirm.html",
        "admin_member_master.entry_exec" => "/admin/member/master/entry_exec.html",
        "admin_member_master.delete" => "/admin/member/master/delete.html",
        "admin_member_master.view_csv" => "/admin/member/master/view_csv.html",
        "admin_member_master.entry_csv_form" => "/admin/member/master/entry_csv_form.html",
        "admin_member_master.entry_csv_confirm" => "/admin/member/master/entry_csv_confirm.html",
        "admin_member_master.entry_csv_exec" => "/admin/member/master/entry_csv_exec.html",
    ));
