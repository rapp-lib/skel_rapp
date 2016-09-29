<?php

/**
 * @controller
 */
class CustomerEntryController extends Controller_App
{
    /**
     * @page
     * @title 会員登録フォーム TOP
     */
    public function act_index ()
    {
        redirect("page:.entry_form");
    }

    /**
     * @page
     * @title 会員登録フォーム 入力フォーム
     */
    public function act_entry_form ()
    {
        $this->context("c",1,true);

        // 入力値のチェック
        if ($_REQUEST["_i"]=="c") {
            $this->c->validate_input($_REQUEST,array(
            ));

            if ($this->c->has_valid_input()) {
                redirect("page:.entry_confirm");
            }
        }

        // id指定があれば既存のデータを読み込む
        if ($_REQUEST["id"]) {
            $this->c->id($_REQUEST["id"]);
            $t =model("Customer")->get_by_id($this->c->id());

            if ( ! $t) {
                $this->c->id(false);
                redirect("page:.view_list");
            }

            $this->c->input($t);
        }
    }

    /**
     * @page
     * @title 会員登録フォーム 確認
     */
    public function act_entry_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();
    }

    /**
     * @page
     * @title 会員登録フォーム 完了
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
            model("Customer")->save($fields,$this->c->id());

            $this->c->clear();
        }
    }

}