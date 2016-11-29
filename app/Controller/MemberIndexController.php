<?php

namespace R\App\Controller;

/**
 * @controller
 */
class MemberIndexController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = true;

    /**
     * @page
     * @title 会員トップ INDEX
     */
    public function act_index ()
    {
    }
    /**
     * @page
     * @title 会員トップ STATIC
     */
    public function act_static ()
    {
    }
}