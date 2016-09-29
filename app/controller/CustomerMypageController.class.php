<?php

/**
 * @controller
 */
class CustomerMypageController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $login_as = customer;
    protected $login_required = true;

    /**
     * @page
     * @title 会員マイページ
     */
    public function act_index ()
    {
    }
}