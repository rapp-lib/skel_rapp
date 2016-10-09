<?php

/**
 * @controller
 */
class MemberRegisterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "member";
    protected $priv_required = false;

    /**
     * @page
     * @title 会員登録 TOP
     */
    public function act_index ()
    {
        redirect("page:.entry_form");
    }

    /**
     * @page
     * @title 会員登録 入力フォーム
     */
    public function act_entry_form ()
    {
        $this->context("c",1,true);

        // 入力値のチェック
        if ($_REQUEST["_i"]=="c") {
            $t = table("Member")->createRecord($_REQUEST);
            $this->c->validate_input($t,array(
            ));
            if ($this->c->has_valid_input()) {
                redirect("page:.entry_confirm");
            }
        }

        // id指定があれば既存のデータを読み込む
        if ($id = $_REQUEST["id"]) {
            $t =table("Member")->selectById($id);
            if ( ! $t) {
                redirect("page:.view_list");
            }
            $this->c->id($id);
            $this->c->input($t);
        }
    }

    /**
     * @page
     * @title 会員登録 確認
     */
    public function act_entry_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();
    }

    /**
     * @page
     * @title 会員登録 完了
     */
    public function act_entry_exec ()
    {
        $this->context("c",1,true);

        if ($this->c->has_valid_input()) {
            // データの記録
            $fields =$this->c->get_fields(array(
                "name",
                "mail",
                "login_pw",
                "favorite_producs",
            ));
            table("Member")->save($this->c->id(),$fields);

            $this->c->clear();
        }
    }

}