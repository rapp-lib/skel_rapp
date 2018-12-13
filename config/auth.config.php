<?php
    return array("auth.roles"=>array(
        "user" => array(
            "login.class" => 'R\Lib\Auth\ConfigBasedLogin',
            "login.options" => array(
                "persist" => "session",
                "auth_table" => "User",
                "login_request_uri" => "id://user_login.login",
                "authenticate" => function($params){
                    if ($params["type"]=="idpw") {
                        // if ("user"==$params["login_id"] && "cftyuhbvg"==$params["login_pw"]) {
                        //    return array("id"=>9999999);
                        // }
                        return table("User")
                            ->authByLoginIdPw($params["login_id"], $params["login_pw"]);
                    }
                },
                "on_logout" => function($old_priv){
                    if ($old_priv) $_SESSION = array();
                    return false;
                },
                "check_priv" => function($priv_req, $priv){
                    if ($priv_req && ! $priv) return false;
                    return true;
                },
                "refresh_priv" => function($priv){
                    if ($priv) {
                        // 強制ログアウト
                        if ($priv["ts_logout"] && $priv["ts_logout"] < time() - 2*60*60) return null;
                        $priv["ts_logout"] = time();
                        // 権限情報の更新
                        if ( ! $priv["ts_refresh"]) $priv["ts_refresh"] = time();
                        if ($priv["ts_refresh"] < time() - 60*60) {
                            return table("User")->selectById($priv["id"]);
                        }
                    }
                    return $priv;
                },
            ),
        ),
        "admin" => array(
            "login.class" => 'R\Lib\Auth\ConfigBasedLogin',
            "login.options" => array(
                "persist" => "session",
                "login_request_uri" => "id://admin_login.login",
                "authenticate" => function($params){
                    if ($params["type"]=="idpw") {
                        if ("admin"==$params["login_id"] && "cftyuhbvg"==$params["login_pw"]) {
                            return array("id"=>9999999);
                        }
                    }
                },
                "on_logout" => function($old_priv){
                    if ($old_priv) $_SESSION = array();
                    return false;
                },
                "check_priv" => function($priv_req, $priv){
                    if ($priv_req && ! $priv) return false;
                    return true;
                },
                "refresh_priv" => function($priv){
                    if ($priv) {
                        // 強制ログアウト
                        if ($priv["ts_logout"] && $priv["ts_logout"] < time() - 2*60*60) return null;
                        $priv["ts_logout"] = time();
                    }
                    return $priv;
                },
            ),
        ),
    ));
