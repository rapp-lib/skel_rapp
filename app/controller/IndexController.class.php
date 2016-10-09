<?php

/**
 * @controller
 */
class IndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "member";
    protected $priv_required = false;

    /**
     * @page
     * @title トップページ
     */
    public function act_index ()
    {
    }
}