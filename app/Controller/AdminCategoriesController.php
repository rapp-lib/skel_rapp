<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminCategoriesController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_categories.list",
        "search_table" => "Category",
        "fields" => array(
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
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * @page
     */
    public function act_detail ()
    {
        $this->vars["t"] = table("Category")->selectById($this->input["id"]);
        if ( ! $this->vars["t"]) return $this->response("notfound");
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_categories.form",
        "csrf_check" => true,
        "table" => "Category",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "category_type"=>array("label"=>"種類"),
            "parent_category_id"=>array("label"=>"大分類ID"),
            "name"=>array("label"=>"分類名"),
            "middle_category_flg"=>array("label"=>"中分類フラグ", "col"=>false),
        ),
        "rules" => array(
            "name",
            "category_type",
            array("parent_category_id", "required", "if"=>array("middle_category_flg"=>"1")),
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
            if ($this->forms["entry"]["id"]) {
                $complete_flg = "update";
            } else {
                $complete_flg = "register";
            }
            $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://.list", array("back"=>"1","complete_flg"=>$complete_flg));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("Category")->deleteById($id);
        }
        return $this->redirect("id://admin_categories.list", array("back"=>"1","complete_flg"=>"delete"));
    }
}
