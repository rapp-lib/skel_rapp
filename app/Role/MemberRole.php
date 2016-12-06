<?php
namespace R\App\Role;

/**
 * @role
 */
class MemberRole extends Role_App
{
    /**
     * @override
     */
    public function loginTrial ($params)
    {
        if ($params["login_id"]) {
            // ログインIDとログインPWを引数に取得
            if ($t = table("Member")->findByLoginIdPw($params["login_id"], $params["login_pass"])->selectOne()) {
                return array("id"=>$t["id"], "privs"=>array());
            }
        }
        return false;
    }

    /**
     * @override
     */
    public function onLoginRequired ($required)
    {
        redirect("page:member_login.login",array(
            "redirect" => $this->isLogin() ? "" : route()->getCurrentRoute()->getUrl(),
        ));
    }

    /**
     * @override
     */
    public function onLogin ()
    {
        session_regenerate_id(true);
    }

    /**
     * @override
     */
    public function onLogout ()
    {
        session_destroy();
    }

    /**
     * @override
     */
    public function onAccess ()
    {
    }
}