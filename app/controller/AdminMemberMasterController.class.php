<?php

/**
 * @controller
 */
class AdminMemberMasterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "admin";
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
     * @title 会員管理 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 会員管理 一覧表示
     */
    public function act_view_list ()
    {
        $this->context("c",1);

        if ($_REQUEST["_i"]=="c") {
            $this->c->clear();
            $this->c->input($_REQUEST);
        }

        $this->vars["ts"] = table("Member")
            ->findBySearchForm($this->list_setting, $this->c->input())
            ->select();
        $this->vars["p"] = $this->vars["ts"]->getPager();
    }

    /**
     * @page
     * @title 会員管理 入力フォーム
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
     * @title 会員管理 確認
     */
    public function act_entry_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();

        redirect("page:.entry_exec");
    }

    /**
     * @page
     * @title 会員管理 完了
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

        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 会員管理 削除
     */
    public function act_delete ()
    {
        $this->context("c");

        // idの指定
        $this->c->id($_REQUEST["id"]);

        // データの削除
        table("Member")->deleteById($this->c->id());

        redirect("page:.view_list");
    }

}