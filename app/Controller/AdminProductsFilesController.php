<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminProductsFilesController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_products_files.list",
        "search_table" => "ProductFile",
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
        if ( ! $this->forms["search"]["product_id"]) return $this->response("badrequest");
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_products_files.form",
        "csrf_check" => true,
        "table" => "ProductFile",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "product_id"=>array("label"=>"製品ID"),
            "file_type"=>array("label"=>"ファイル種別"),
            "common_file_id"=>array("label"=>"ファイル属性"),
            "file"=>array("label"=>"ファイル", "storage"=>"public"),
            "link"=>array("label"=>"リンクURL"),
            "release_date"=>array("label"=>"公開日"),
            "description"=>array("label"=>"説明"),
            "display_status"=>array("label"=>"公開状態"),
            "product_name"=> array("label"=>"製品名", "col"=>false),
        ),
        "rules" => array(
            "file_type",
             array("file", "required", "if"=>array("link"=>false,"or"=>array("common_file_id"=>false)), "message"=>"ファイルまたはリンクURLを入力してください。"),
             array("link", "required", "if"=>array("file"=>false,"or"=>array("common_file_id"=>false)), "message"=>"ファイルまたはリンクURLを入力してください。"),
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
            if ( ! $this->forms["entry"]["product_id"]) {
                $this->forms["entry"]["product_id"] = $this->input["product_id"];
                $product_t = table("Product")->selectById($this->forms["entry"]["product_id"]);
                $this->forms["entry"]["product_name"] = $product_t["name"];
            }
        }
        if ( ! $this->forms["entry"]["product_id"]) return $this->redirect("id://admin_products.list");
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
            if ($this->forms["entry"]["id"]) {
                $complete_flg = "files_update";
            } else {
                $complete_flg = "files_register";
            }
            $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://admin_products.detail", array("id"=>$t["product_id"],"complete_flg"=>$complete_flg));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            $t = table("ProductFile")->selectById($id);
            table("ProductFile")->deleteById($id);
        }
        return $this->redirect("id://admin_products.detail", array("id"=>$t["product_id"],"complete_flg"=>"files_delete"));
    }
}
