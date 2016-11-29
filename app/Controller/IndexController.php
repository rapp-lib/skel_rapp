<?php

namespace R\App\Controller;

/**
 * @controller
 */
class IndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = false;

    /**
     * @page
     * @title トップページ INDEX
     */
    public function act_index ()
    {
    }
    /**
     * @page
     * @title トップページ STATIC
     */
    public function act_static ()
    {
    }
}