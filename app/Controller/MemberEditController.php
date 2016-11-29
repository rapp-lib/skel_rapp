<?php

namespace R\App\Controller;

/**
 * @controller
 */
class MemberEditController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = true;

    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "auto_restore" => true,
        "form_page" => ".entry_form",
        "table" => "Member",
        "fields" => array(
            "id",
            "name",
            "nickname",
            "mail",
            "gender",
            "birthday",
            "job",
            "interest",
            "login_id",
            "login_pw",
        ),
        "rules" => array(
        ),
    );

    /**
     * @page
     * @title 会員情報変更 TOP
     */
    public function act_index ()
    {
        redirect("page:.entry_form");
    }

    /**
     * @page
     * @title 会員情報変更 入力フォーム
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
     * @title 会員情報変更 確認
     */
    public function act_entry_confirm ()
    {
    }

    /**
     * @page
     * @title 会員情報変更 完了
     */
    public function act_entry_exec ()
    {
        if ( ! $this->forms["entry"]->isEmpty()) {
            if ( ! $this->forms["entry"]->isValid()) {
                redirect("page:.entry_form", array("back"=>"1"));
            }
            $this->forms["entry"]->getRecord()->save();
            $this->forms["entry"]->clear();
        }
    }

}
