<?php

namespace R\App\Controller;

/**
 * @controller
 */
class ContactController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = false;

    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "auto_restore" => true,
        "form_page" => ".entry_form",
        "fields" => array(
            "category",
            "content",
            "name",
            "mail",
        ),
        "rules" => array(
        ),
    );

    /**
     * @page
     * @title 問い合わせフォーム TOP
     */
    public function act_index ()
    {
        redirect("page:.entry_form");
    }

    /**
     * @page
     * @title 問い合わせフォーム 入力フォーム
     */
    public function act_entry_form ()
    {
        if ($this->forms["entry"]->receive()) {
            if ($this->forms["entry"]->isValid()) {
                $this->forms["entry"]->save();
                redirect("page:.entry_confirm");
            }
        } elseif ($id = $this->request["id"]) {
            $this->forms["entry"]->init($id);
        } elseif ( ! $this->request["back"]) {
            $this->forms["entry"]->clear();
        }
    }

    /**
     * @page
     * @title 問い合わせフォーム 確認
     */
    public function act_entry_confirm ()
    {
    }

    /**
     * @page
     * @title 問い合わせフォーム 完了
     */
    public function act_entry_exec ()
    {
        if ( ! $this->forms["entry"]->isEmpty()) {
            if ( ! $this->forms["entry"]->isValid()) {
                redirect("page:.entry_form", array("back"=>"1"));
            }
            // メールの送信
            $this->send_mail(array(
                "template" => "sample",
                "vars" => array(
                    "form" => $this->forms["entry"],
                ),
            ));
            $this->forms["entry"]->clear();
        }
    }

}
