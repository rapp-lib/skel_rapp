<?php

/**
 * @controller
 */
class IndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $login_as = null;
    protected $login_required = false;

    /**
     * @page
     * @title トップページ
     */
    public function act_index ()
    {
    }
}