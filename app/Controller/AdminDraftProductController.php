<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminDraftProductController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_draft_product.list",
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
        $this->vars["ts"] = $this->forms["search"]->search()->select();
    }
    /**
     * @page
     */
    public function act_detail ()
    {
        $this->vars["t"] = table("Product")->selectById($this->input["id"]);
        if ( ! $this->vars["t"]) return $this->response("notfound");
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_draft_product.form",
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
            "status"=>array("label"=>"ステータス"),
        ),
        "rules" => array(
            "name",
            "status",
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
                return $this->redirect("id://.form_complete");
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
            $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://.list", array("back"=>"1"));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("Product")->deleteById($id);
        }
        return $this->redirect("id://admin_draft_product.list", array("back"=>"1"));
    }
}
