<?php
    return array("http.webroots.www.routes"=>array(
        // guest
        array(array(
            // トップ
            array("index.index", "/"),
            array("index.index_static", "/{FILE:.+}", array("static_route"=>true)),
            // 対象製品一覧
            array("product.list", "/product/"),
            // ユーザー登録
            array("user_register.form", "/user/register/"),
            array("user_register.form_confirm", "/user/register/form_confirm.html"),
            array("user_register.form_complete", "/user/register/form_complete.html"),
        ), "", array("auth.role"=>"guest")),
        // user
        array(array(
            // ユーザーログイン
            array("user_login.login", "/user/login/", array("auth.priv_req"=>false)),
            array("user_login.login_exit", "/user/login/login_exit.html", array("auth.priv_req"=>false)),
            array("user_login.reminder", "/user/login/reminder.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_send", "/user/login/reminder_send.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_reset", "/user/login/reminder_reset.html", array("auth.priv_req"=>false)),
            array("user_login.reminder_complete", "/user/login/reminder_complete.html", array("auth.priv_req"=>false)),
            // ユーザーマイページ
            array("user_index.index", "/user/"),
            array("user_index.index_static", "/user/{FILE:.+}", array("static_route"=>true)),
            // ユーザー編集
            array("user_edit.form", "/user/edit/"),
            array("user_edit.form_confirm", "/user/edit/form_confirm.html"),
            array("user_edit.form_complete", "/user/edit/form_complete.html"),
            // ユーザー製品追加
            array("user_product_edit.form", "/user/product/edit/"),
            array("user_product_edit.form_confirm", "/user/product/edit/form_confirm.html"),
            array("user_product_edit.form_complete", "/user/product/edit/form_complete.html"),
        ), "", array("auth.role"=>"user", "auth.priv_req"=>true)),
        // admin
        array(array(
            // 管理者ログイン
            array("admin_login.login", "/admin/login/", array("auth.priv_req"=>false)),
            array("admin_login.login_exit", "/admin/login/login_exit.html", array("auth.priv_req"=>false)),
            // 管理者マイページ
            array("admin_index.index", "/admin/"),
            array("admin_index.index_static", "/admin/{FILE:.+}", array("static_route"=>true)),
            // 承認待ちユーザー管理
            array("admin_accept_user.list", "/admin/accept/user/"),
            array("admin_accept_user.form", "/admin/accept/user/form.html"),
            array("admin_accept_user.form_confirm", "/admin/accept/user/form_confirm.html"),
            array("admin_accept_user.form_complete", "/admin/accept/user/form_complete.html"),
            array("admin_accept_user.delete", "/admin/accept/user/delete.html", array("csrf_check"=>true)),
            // ユーザー管理
            array("admin_user.list", "/admin/user/"),
            array("admin_user.form", "/admin/user/form.html"),
            array("admin_user.form_confirm", "/admin/user/form_confirm.html"),
            array("admin_user.form_complete", "/admin/user/form_complete.html"),
            array("admin_user.delete", "/admin/user/delete.html", array("csrf_check"=>true)),
            array("admin_user.csv", "/admin/user/csv.html"),
            // ユーザー製品管理
            array("admin_user_product.list", "/admin/user/product/"),
            array("admin_user_product.form", "/admin/user/product/form.html"),
            array("admin_user_product.form_confirm", "/admin/user/product/form_confirm.html"),
            array("admin_user_product.form_complete", "/admin/user/product/form_complete.html"),
            array("admin_user_product.delete", "/admin/user/product/delete.html", array("csrf_check"=>true)),
            // 製品管理
            array("admin_product.list", "/admin/product/"),
            array("admin_product.form", "/admin/product/form.html"),
            array("admin_product.form_confirm", "/admin/product/form_confirm.html"),
            array("admin_product.form_complete", "/admin/product/form_complete.html"),
            array("admin_product.delete", "/admin/product/delete.html", array("csrf_check"=>true)),
            // 下書き製品管理
            array("admin_draft_product.list", "/admin/draft/product/"),
            array("admin_draft_product.detail", "/admin/draft/product/detail.html"),
            array("admin_draft_product.form", "/admin/draft/product/form.html"),
            array("admin_draft_product.form_confirm", "/admin/draft/product/form_confirm.html"),
            array("admin_draft_product.form_complete", "/admin/draft/product/form_complete.html"),
            array("admin_draft_product.delete", "/admin/draft/product/delete.html", array("csrf_check"=>true)),
            // 関連ファイル管理
            array("admin_relation_product.list", "/admin/relation/product/"),
            array("admin_relation_product.form", "/admin/relation/product/form.html"),
            array("admin_relation_product.form_confirm", "/admin/relation/product/form_confirm.html"),
            array("admin_relation_product.form_complete", "/admin/relation/product/form_complete.html"),
            array("admin_relation_product.delete", "/admin/relation/product/delete.html", array("csrf_check"=>true)),
            // 分類管理
            array("admin_product_category.list", "/admin/product/category/"),
            array("admin_product_category.form", "/admin/product/category/form.html"),
            array("admin_product_category.form_confirm", "/admin/product/category/form_confirm.html"),
            array("admin_product_category.form_complete", "/admin/product/category/form_complete.html"),
            array("admin_product_category.delete", "/admin/product/category/delete.html", array("csrf_check"=>true)),
            // 共通関連情報管理
            array("admin_common_information.list", "/admin/common/information/"),
            array("admin_common_information.form", "/admin/common/information/form.html"),
            array("admin_common_information.form_confirm", "/admin/common/information/form_confirm.html"),
            array("admin_common_information.form_complete", "/admin/common/information/form_complete.html"),
            array("admin_common_information.delete", "/admin/common/information/delete.html", array("csrf_check"=>true)),
            // 更新情報管理
            array("admin_news.list", "/admin/news/"),
            array("admin_news.form", "/admin/news/form.html"),
            array("admin_news.form_confirm", "/admin/news/form_confirm.html"),
            array("admin_news.form_complete", "/admin/news/form_complete.html"),
            array("admin_news.delete", "/admin/news/delete.html", array("csrf_check"=>true)),
            // ご注意事項管理
            array("admin_notes.list", "/admin/notes/"),
            array("admin_notes.detail", "/admin/notes/detail.html"),
            array("admin_notes.form", "/admin/notes/form.html"),
            array("admin_notes.form_confirm", "/admin/notes/form_confirm.html"),
            array("admin_notes.form_complete", "/admin/notes/form_complete.html"),
            array("admin_notes.delete", "/admin/notes/delete.html", array("csrf_check"=>true)),
        ), "", array("auth.role"=>"admin", "auth.priv_req"=>true)),
    ));
