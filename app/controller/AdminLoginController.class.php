<?php

/**
 * @controller
 */
class AdminLoginController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $login_as = null;
    protected $login_required = false;

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
     * @title 管理者ログイン ログイン
     */
    public function act_login ()
    {
        $this->context("c",1,true);

        // 転送先指定の保存
        if ($_REQUEST["redirect_to"]) {
            $redirect_to =sanitize_decode($_REQUEST["redirect_to"]);
            $this->c->session("redirect_to",$redirect_to);
        }

        // 入力値のチェック
        if ($_REQUEST["_i"]=="c") {
            $this->c->validate_input($_REQUEST,array());
            $result = auth()->login("admin", $this->c->input());

            if ($result) {

                // 転送先の指定があればそちらを優先
                if ($this->c->session("redirect_to")) {
                    redirect($this->c->session("redirect_to"));
                }

                redirect("page:index.index");

            } else {

                $this->vars["login_error"] =true;
            }
        }
    }

    /**
     * @page
     * @title 管理者ログイン ログアウト
     */
    public function act_logout ()
    {
        $this->context("c");

        // ログアウト処理
        auth()->logout("admin");

        redirect("page:index.index");
    }
}