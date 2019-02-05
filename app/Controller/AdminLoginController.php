<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminLoginController extends Controller_Admin
{
    /**
     * ログインフォーム
     */
    protected static $form_login = array(
        "form_page" => ".index",
        "fields" => array(
            "login_id",
            "login_pw",
            "redirect",
        ),
        "rules" => array(
        ),
    );
    /**
     * @page
     */
    public function act_login ()
    {
        if ($this->forms["login"]->receive($this->input)) {
            if ($this->forms["login"]->isValid()) {
                // ログイン処理
                $result = app()->user->authenticate("admin", array(
                    "type" => "idpw",
                    "login_id" => $this->forms["login"]["login_id"],
                    "login_pw" => $this->forms["login"]["login_pw"],
                ));
                if ($result) {
                    return $this->redirect($this->forms["login"]["redirect"] ?: "id://admin_index.index");
                } else {
                    $this->vars["login_error"] = true;
                }
            }
        // 転送先の設定
        } elseif ($redirect = $this->input["redirect"]) {
            $this->forms["login"]["redirect"] = $redirect;
        }
    }
    /**
     * @page
     */
    public function act_login_exit ()
    {
        // ログアウト処理
        app()->user->setPriv("admin",false);
        // // ログアウト後の転送処理
        // return $this->redirect("id://.login");
    }
}
