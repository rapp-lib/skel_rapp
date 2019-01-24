<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserIndexController extends Controller_User
{
    /**
     * @page
     */
    public function act_index ()
    {
    }
    /**
     * @page
     */
    public function act_user_login_header ()
    {
        $this->vars["head_user_t"] = table("User")->findMine()->selectOne();
    }
    /**
     * @page
     */
    public function act_index_static ()
    {
    }
}
