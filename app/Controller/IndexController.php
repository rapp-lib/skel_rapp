<?php
namespace R\App\Controller;

/**
 * @controller
 */
class IndexController extends Controller_Guest
{
    /**
     * @page
     */
    public function act_index ()
    {
        // ログイン画面をトップページにする
        return $this->redirect("id://user_login.login");
    }
    /**
     * @page
     */
    public function act_index_static ()
    {
    }
}
