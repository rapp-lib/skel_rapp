<?php
namespace R\App\Role;

/**
 *
 */
class CustomerRole extends Role_App
{
    /**
     * @override
     */
    public function loginTrial ($params)
    {
        $t = array();

        if ($params["login_id"]) {
            $t = $params["login_id"]=="test" && $params["login_pass"]=="cftyuhbvg"
                ? array("id"=>1, "privs"=>array()) : array();
        }

        if ( ! $t) {
            return false;
        }

        return $t;
    }

    /**
     * @override
     */
    public function onLogin ()
    {
        // Session Fixation対策
        session_regenerate_id(true);
    }

    /**
     * @override
     */
    public function onLogout ()
    {
        // ログアウト時にSession破棄
        session_destroy();
    }

    /**
     * @override
     */
    public function onBeforeAuthenticate ()
    {
    }

    /**
     * @override
     */
    public function onLoginRequired ()
    {
        redirect("page:customer_login.login",array(
            "redirect_to" => registry("Request.request_uri"),
        ));
    }
}