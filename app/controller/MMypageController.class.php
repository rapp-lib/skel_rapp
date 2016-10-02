<?php

/**
 * @controller
 */
class MMypageController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = member;
    protected $priv_required = true;

    /**
     * @page
     * @title 会員マイページ
     */
    public function act_index ()
    {
    }
}