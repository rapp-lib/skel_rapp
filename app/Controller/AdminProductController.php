<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminProductController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_product.list",
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
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_product.form",
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
            "relation_products"=>array("label"=>"関連ファイル"),
            "relation_products.*.id",
            "relation_products.*.ord_seq"=>array("col"=>false),
            "relation_products.*.class"=>array("label"=>"ファイル種別"),
            "relation_products.*.common_information_id"=>array("label"=>"ファイル属性"),
            "relation_products.*.file"=>array("label"=>"ファイル", "storage"=>"public"),
            "relation_products.*.link"=>array("label"=>"リンク"),
            "relation_products.*.release_date"=>array("label"=>"公開日"),
            "relation_products.*.description"=>array("label"=>"説明"),
            "relation_products.*.status"=>array("label"=>"ステータス"),
        ),
        "rules" => array(
            "name",
            "status",
            "relation_products.*.class",
            "relation_products.*.status",
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
        return $this->redirect("id://admin_product.list", array("back"=>"1"));
    }
}
