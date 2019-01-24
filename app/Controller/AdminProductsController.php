<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminProductsController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_products.list",
        "search_table" => "Product",
        "fields" => array(
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("id")),
        ),
    );
    /**
     * @page
     */
    public function act_list ()
    {
        if ($this->input["back"]) {
            $this->forms["search"]->restore();
        } elseif ($this->forms["search"]->receive($this->input)) {
            $this->forms["search"]->save();
        }
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("display_status","1")->select();
    }
    /**
     * @page
     */
    public function act_draft_list ()
    {
        if ($this->input["back"]) {
            $this->forms["search"]->restore();
        } elseif ($this->forms["search"]->receive($this->input)) {
            $this->forms["search"]->save();
        }
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("display_status","2")->select();
    }
    /**
     * @page
     */
    public function act_detail ()
    {
        $this->vars["t"] = table("Product")->selectById($this->input["id"]);
        if ( ! $this->vars["t"]) return $this->response("notfound");
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_products.form",
        "csrf_check" => true,
        "table" => "Product",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "parent_category_id"=>array("label"=>"大分類ID"),
            "child_category_id"=>array("label"=>"中分類ID"),
            "name"=>array("label"=>"製品名"),
            "model"=>array("label"=>"型名"),
            "image"=>array("label"=>"画像", "storage"=>"public"),
            "manual"=>array("label"=>"取扱説明書", "storage"=>"public"),
            "release_date"=>array("label"=>"公開日"),
            "description"=>array("label"=>"説明"),
            "display_status"=>array("label"=>"公開ステータス"),
            "register_flg"=>array("label"=>"新規登録フラグ", "col"=>false),
        ),
        "rules" => array(
            "name",
            "display_status",
        ),
    );
    /**
     * @page
     */
    public function act_form ()
    {
        if ($this->forms["entry"]->receive($this->input)) {
            if ($this->forms["entry"]->isValid()) {
                $this->forms["entry"]->save();
                return $this->redirect("id://.form_confirm");
            }
        } elseif ($this->input["back"]) {
            $this->forms["entry"]->restore();
        } else {
            $this->forms["entry"]->clear();
            if ($id = $this->input["id"]) {
                $t = $this->forms["entry"]->getTable()->selectById($id);
                if ( ! $t) return $this->response("notfound");
                $this->forms["entry"]->setRecord($t);
            }
            // 新規登録画面切り替えフラグの設定
            // 新規登録時と、下書きの編集時にフラグON
            if (! $this->input["id"] || $t["display_status"] == "2"){
                $this->forms["entry"]["register_flg"] ="1";
            }
        }
    }
    /**
     * @page
     */
    public function act_form_confirm ()
    {
        $this->forms["entry"]->restore();
        $this->vars["t"] = $this->forms["entry"]->getRecord();
    }
    /**
     * @page
     */
    public function act_form_complete ()
    {
        $this->forms["entry"]->restore();
        if ( ! $this->forms["entry"]->isEmpty() && $this->forms["entry"]->isValid()) {
            if ($this->forms["entry"]["register_flg"] && $this->forms["entry"]["display_status"] == "2") {
                $complete_flg = "draft";
            } else if ($this->forms["entry"]["register_flg"]) {
                $complete_flg = "register";
            } else {
                $complete_flg = "update";
            }
            $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://.detail", array("back"=>"1","id"=>$t["id"],"complete_flg"=>$complete_flg));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("Product")->deleteById($id);
        }
        return $this->redirect("id://admin_products.list", array("back"=>"1","complete_flg"=>"delete"));
    }
}
