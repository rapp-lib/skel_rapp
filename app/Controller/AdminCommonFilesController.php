<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminCommonFilesController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_common_files.list",
        "search_table" => "CommonFile",
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
        "form_page" => "admin_common_files.form",
        "csrf_check" => true,
        "table" => "CommonFile",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "file"=>array("label"=>"ファイル", "storage"=>"public"),
            "title"=>array("label"=>"タイトル"),
            "release_date"=>array("label"=>"公開日"),
            "description"=>array("label"=>"説明"),
        ),
        "rules" => array(
            "file",
            "title",
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
            table("CommonFile")->deleteById($id);
        }
        return $this->redirect("id://admin_common_files.list", array("back"=>"1"));
    }
}
