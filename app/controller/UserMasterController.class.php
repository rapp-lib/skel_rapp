<?php

/**
 * @controller
 */
class UserMasterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = admin;
    protected $priv_required = true;

    /**
     * 検索フォーム設定
     */
    protected $list_setting =array(
        "search" =>array(
            "name" =>array(
                    "type" =>'eq',
                    "target" =>"name"),
            "mail" =>array(
                    "type" =>'eq',
                    "target" =>"mail"),
            "favorite_producs" =>array(
                    "type" =>'eq',
                    "target" =>"favorite_producs"),
        ),
        "sort" =>array(
            "sort_param_name" =>"sort",
            "default" =>"id@ASC",
        ),
        "paging" =>array(
            "offset_param_name" =>"offset",
            "limit" =>20,
            "slider" =>10,
        ),
    );

    /**
     * @page
     * @title ユーザ管理 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title ユーザ管理 一覧表示
     */
    public function act_view_list ()
    {
        $this->context("c",1);

        if ($_REQUEST["_i"]=="c") {
            $this->c->clear();
            $this->c->input($_REQUEST);
        }

        list($this->vars["ts"] ,$this->vars["p"]) = table("Customer")            ->findBySearchForm($this->list_setting, $this->c->input())
            ->selectPagenate();
    }

    /**
     * @page
     * @title ユーザ管理 入力フォーム
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
            $t =table("Customer")->selectById($this->c->id());

            if ( ! $t) {
                $this->c->id(false);
                redirect("page:.view_list");
            }

            $this->c->input($t);
        }
    }

    /**
     * @page
     * @title ユーザ管理 確認
     */
    public function act_entry_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();

        redirect("page:.entry_exec");
    }

    /**
     * @page
     * @title ユーザ管理 完了
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
            table("Customer")->save($this->c->id(),$fields);

            $this->c->clear();
        }

        redirect("page:.view_list");
    }

    /**
     * @page
     * @title ユーザ管理 削除
     */
    public function act_delete ()
    {
        $this->context("c");

        // idの指定
        $this->c->id($_REQUEST["id"]);

        // データの削除
        table("Customer")->deleteById($this->c->id());

        redirect("page:.view_list");
    }

}