<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserProductsController extends Controller_User
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "user_products.list",
        "search_table" => "UserProduct",
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
        $this->vars["ts"] = $this->forms["search"]->search()->findMine()->select();
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "user_products.form",
        "csrf_check" => true,
        "table" => "UserProduct",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "product_id"=>array("label"=>"型名（製品名）"),
            "serial_number"=>array("label"=>"シリアルNo."),
            "purchase_source"=>array("label"=>"購入元"),
            "purchase_reason"=>array("label"=>"購入理由"),
        ),
        "rules" => array(
            "serial_number",
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
                $t = $this->forms["entry"]->getTable()->findMine()->selectById($id);
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
            $t = $this->forms["entry"]->getTableWithValues()->saveMine()->getSavedRecord();
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
            table("UserProduct")->findMine()->deleteById($id);
        }
        return $this->redirect("id://user_products.list", array("back"=>"1"));
    }
}
