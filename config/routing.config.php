<?php
    return array("http.webroots.www.routes"=>array(
        // guest
        array(array(
            // 対象製品一覧
            array("products.list", "/users/product.html"),
            // 更新情報一覧
            array("user_news.list", "/users/news.html"),
            // ご注意事項一覧
            array("user_notices.list", "/users/notices/"),
            // ユーザー登録
            array("user_register.form", "/users/register.html"),
            array("user_register.form_confirm", "/users/register_confirm.html"),
            array("user_register.form_complete", "/users/register_complete.html"),
            // ユーザートップ
            array("user_index.user_login_header", "/users/include/user_login_header.html", array("include"=>true)),
            array("user_index.index_static", "/users/{FILE:.+}", array("static_route"=>true)),
        ), "", array("auth.role"=>"guest")),
        // user
        array(array(
            // ユーザーログイン
            array("user_login.login", "/users/", array("auth.priv_req"=>false)),
            array("user_login.login_exit", "/users/logout.html", array("auth.priv_req"=>false)),
            array("user_login.reminder", "/users/reminder.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_send", "/users/reminder_send.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_reset", "/users/reminder_reset.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_complete", "/users/reminder_complete.html", array("auth.priv_req"=>false)),
            // マイページ
            array("user_products.list", "/users/mypage.html"),
            array("user_products.form", "/users/product_register.html"),
            array("user_products.form_confirm", "/users/product_register_confirm.html"),
            array("user_products.form_complete", "/users/product_register_complete.html"),
            array("user_products.delete", "/users/products/delete.html", array("csrf_check"=>true)),
            // 関連ファイルダウンロード
            array("user_products_files.list", "/users/product_file.html"),
            // 登録情報編集
            array("user_edit.form", "/users/user_edit.html"),
            array("user_edit.form_confirm", "/users/user_edit_confirm.html"),
            array("user_edit.form_complete", "/users/user_edit_complete.html"),
        ), "", array("auth.role"=>"user", "auth.priv_req"=>true)),
        // admin
        array(array(
            // 管理者ログイン
            array("admin_login.login", "/admin/login/", array("auth.priv_req"=>false)),
            array("admin_login.login_exit", "/admin/login/login_exit.html", array("auth.priv_req"=>false)),
            // 管理者マイページ
            array("admin_index.index", "/admin/"),
            array("admin_index.left_menu_user", "/admin/include/admin_left_menu_user.html", array("include"=>true)),
            array("admin_index.left_menu_product", "/admin/include/admin_left_menu_product.html", array("include"=>true)),
            array("admin_index.index_static", "/admin/{FILE:.+}", array("static_route"=>true)),
            array("admin_index.test", "/admin/test.html"),
            // ユーザー管理
            array("admin_users.list", "/admin/users/"),
            array("admin_users.detail", "/admin/users/detail.html"),
            array("admin_users.form", "/admin/users/form.html"),
            array("admin_users.form_confirm", "/admin/users/form_confirm.html"),
            array("admin_users.form_complete", "/admin/users/form_complete.html"),
            array("admin_users.delete", "/admin/users/delete.html", array("csrf_check"=>true)),
            array("admin_users.csv", "/admin/users/csv.html"),
            array("admin_users.csvdl", "/admin/users/csvdl.html"),
            array("admin_users.csvdl_csv", "/admin/users/csvdl_csv.html"),
            array("admin_users.csvdl_delete", "/admin/users/csvdl_delete.html"),
            // ユーザー承認管理
            array("admin_users_accept.list", "/admin/users/accept/"),
            array("admin_users_accept.form", "/admin/users/accept/form.html"),
            array("admin_users_accept.form_confirm", "/admin/users/accept/form_confirm.html"),
            array("admin_users_accept.form_complete", "/admin/users/accept/form_complete.html"),
            array("admin_users_accept.delete", "/admin/users/accept/delete.html", array("csrf_check"=>true)),
            // ユーザー製品管理
            array("admin_users_products.list", "/admin/users/products/"),
            array("admin_users_products.form", "/admin/users/products/form.html"),
            array("admin_users_products.form_confirm", "/admin/users/products/form_confirm.html"),
            array("admin_users_products.form_complete", "/admin/users/products/form_complete.html"),
            array("admin_users_products.delete", "/admin/users/products/delete.html", array("csrf_check"=>true)),
            array("admin_users_products.csv", "/admin/users/products/csv.html"),
            // 製品管理
            array("admin_products.list", "/admin/products/"),
            array("admin_products.draft_list", "/admin/products/draft_list.html"),
            array("admin_products.detail", "/admin/products/detail.html"),
            array("admin_products.form", "/admin/products/form.html"),
            array("admin_products.form_confirm", "/admin/products/form_confirm.html"),
            array("admin_products.form_complete", "/admin/products/form_complete.html"),
            array("admin_products.delete", "/admin/products/delete.html", array("csrf_check"=>true)),
            // 関連ファイル管理
            array("admin_products_files.list", "/admin/products/files/"),
            array("admin_products_files.form", "/admin/products/files/form.html"),
            array("admin_products_files.form_confirm", "/admin/products/files/form_confirm.html"),
            array("admin_products_files.form_complete", "/admin/products/files/form_complete.html"),
            array("admin_products_files.delete", "/admin/products/files/delete.html", array("csrf_check"=>true)),
            // 分類管理
            array("admin_categories.list", "/admin/categories/"),
            array("admin_categories.detail", "/admin/categories/detail.html"),
            array("admin_categories.form", "/admin/categories/form.html"),
            array("admin_categories.form_confirm", "/admin/categories/form_confirm.html"),
            array("admin_categories.form_complete", "/admin/categories/form_complete.html"),
            array("admin_categories.delete", "/admin/categories/delete.html", array("csrf_check"=>true)),
            // 共通関連情報管理
            array("admin_common_files.list", "/admin/common/files/"),
            array("admin_common_files.form", "/admin/common/files/form.html"),
            array("admin_common_files.form_confirm", "/admin/common/files/form_confirm.html"),
            array("admin_common_files.form_complete", "/admin/common/files/form_complete.html"),
            array("admin_common_files.delete", "/admin/common/files/delete.html", array("csrf_check"=>true)),
            // 更新情報管理
            array("admin_news.list", "/admin/news/"),
            array("admin_news.detail", "/admin/news/detail.html"),
            array("admin_news.form", "/admin/news/form.html"),
            array("admin_news.form_confirm", "/admin/news/form_confirm.html"),
            array("admin_news.form_complete", "/admin/news/form_complete.html"),
            array("admin_news.delete", "/admin/news/delete.html", array("csrf_check"=>true)),
            // ご注意事項管理
            array("admin_notices.list", "/admin/notices/"),
            array("admin_notices.detail", "/admin/notices/detail.html"),
            array("admin_notices.form", "/admin/notices/form.html"),
            array("admin_notices.form_confirm", "/admin/notices/form_confirm.html"),
            array("admin_notices.form_complete", "/admin/notices/form_complete.html"),
            array("admin_notices.delete", "/admin/notices/delete.html", array("csrf_check"=>true)),
        ), "", array("auth.role"=>"admin", "auth.priv_req"=>true)),
    ));
