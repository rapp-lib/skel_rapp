<?php

namespace R\App\Controller;

/**
 * @controller
 */
class AdminIndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "admin";
    protected static $priv_required = true;

    /**
     * @page
     * @title 管理者トップ INDEX
     */
    public function act_index ()
    {
    }
    /**
     * @page
     * @title 管理者トップ STATIC
     */
    public function act_static ()
    {
    }
}