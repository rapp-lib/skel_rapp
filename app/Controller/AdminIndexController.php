<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminIndexController extends Controller_Admin
{
    /**
     * @page
     */
    public function act_index ()
    {
        // ユーザー登録承認待ち件数取得
        $this->vars["accept_user_count"] = table("User")->findBy("accept_flg","1")->selectCount();

        // 製品登録承認待ち件数取得
        $this->vars["accept_product_count"] = table("UserProduct")->findBy("accept_flg","1")->selectCount();
    }
    /**
     * @page
     */
    public function act_left_menu_user ()
    {
        // ユーザー登録承認待ち件数取得
        $this->vars["accept_user_count"] = table("User")->findBy("accept_flg","1")->selectCount();
    }
    /**
     * @page
     */
    public function act_left_menu_product ()
    {
        // 製品登録承認待ち件数取得
        $this->vars["accept_product_count"] = table("UserProduct")->findBy("accept_flg","1")->selectCount();
    }
    /**
     * @page
     */
    public function act_index_static ()
    {
    }
    /**
     * @page
     */
    public function act_test ()
    {
        phpinfo();
    }
}
