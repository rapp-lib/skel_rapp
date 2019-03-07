<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminNewsController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_news.list",
        "search_table" => "News",
        "fields" => array(
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("date DESC")),
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
        $this->vars["t"] = table("News")->selectById($this->input["id"]);
        if ( ! $this->vars["t"]) return $this->response("notfound");
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_news.form",
        "csrf_check" => true,
        "table" => "News",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "date"=>array("label"=>"年月日"),
            "contents"=>array("label"=>"内容"),
        ),
        "rules" => array(
            "date",
            "contents",
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
        if ($complete_flg == "update") {
            return $this->redirect("id://.detail", array("back"=>"1","id"=>$t["id"],"complete_flg"=>$complete_flg));
        } else {
            return $this->redirect("id://.list", array("back"=>"1","complete_flg"=>$complete_flg));
        }
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("News")->deleteById($id);
        }
        return $this->redirect("id://admin_news.list", array("back"=>"1","complete_flg"=>"delete"));
    }
}
