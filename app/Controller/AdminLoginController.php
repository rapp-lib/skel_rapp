<?php

namespace R\App\Controller;

/**
 * @controller
 */
class AdminLoginController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "admin";
    protected static $priv_required = false;


    /**
     * ログインフォーム
     */
    protected static $form_login = array(
        "form_page" => ".index",
        "fields" => array(
            "login_id",
            "login_pass",
            "redirect",
        ),
        "rules" => array(
        ),
    );

    /**
     * @page
     * @title 管理者ログイン TOP
     */
    public function act_index ()
    {
        redirect("page:.login");
    }

    /**
     * @page
     * @title 管理者ログイン ログインフォーム
     */
    public function act_login ()
    {
        if ($this->forms["login"]->receive()) {
            if ($this->forms["login"]->isValid()) {
                // ログイン処理
                if (auth()->login("admin", $this->forms["login"])) {
                    // ログイン成功時の転送処理
                    if ($redirect = $this->forms["login"]["redirect"]) {
                        response()->redirectUrl($redirect);
                    } else {
                        response()->redirect("admin_index.index");
                    }
                } else {
                    $this->vars["login_error"] = true;
                }
            }
        // 転送先の設定
        } elseif ($redirect = $this->request["redirect"]) {
            $this->forms["login"]["redirect"] = sanitize_decode($redirect);
        }
    }

    /**
     * @page
     * @title 管理者ログイン ログアウト
     */
    public function act_logout ()
    {
        // ログアウト処理
        auth()->logout("admin");
        // ログアウト後の転送処理
        redirect("page:.login");
    }
}
