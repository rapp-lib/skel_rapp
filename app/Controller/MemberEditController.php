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
            "name", "nickname", "mail", "gender", "login_id",
            array("mail", "format", "format"=>"mail"),
            array("login_pw", "length", "min"=>6, "max"=>12),
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

        // 認証成功したメンバーのIDから、会員情報を引き継ぐ
        } elseif ($id = auth("member")->getId()) {
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
