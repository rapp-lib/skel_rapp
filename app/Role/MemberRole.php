<?php
namespace R\App\Role;

/**
 *
 */
class MemberRole extends Role_App
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
    public function onLoginRequired ($required)
    {
        redirect("page:member.login",array(
            "redirect_to" => $this->getAttr("login") ? "" : registry("Request.request_uri"),
        ));
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
        session_destroy();
    }

    /**
     * @override
     */
    public function onAccess ()
    {
    }
}