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
        $result = false;
        if ($params["login_id"]) {
            if ($params["login_id"]=="test" && $params["login_pass"]=="cftyuhbvg") {
                $result = array("id"=>1, "privs"=>array());
            }
        }
        return $result;
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