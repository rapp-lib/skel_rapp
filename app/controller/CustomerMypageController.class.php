<?php

/**
 * @controller
 */
class CustomerMypageController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = customer;
    protected $priv_required = true;

    /**
     * @page
     * @title 会員マイページ
     */
    public function act_index ()
    {
    }
}