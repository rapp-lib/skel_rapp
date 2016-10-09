<?php

/**
 * @controller
 */
class MemberIndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "member";
    protected $priv_required = true;

    /**
     * @page
     * @title 会員トップ
     */
    public function act_index ()
    {
    }
}