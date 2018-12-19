<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminUserProductController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_user_product.list",
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
        $this->vars["ts"] = $this->forms["search"]->search()->select();
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_user_product.form",
        "csrf_check" => true,
        "table" => "UserProduct",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "product_id"=>array("label"=>"製品ID"),
            "model"=>array("label"=>"型名（製造名）"),
            "serial_number"=>array("label"=>"シリアルNo"),
            "purchase_source"=>array("label"=>"購入元"),
            "purchase_reason"=>array("label"=>"購入理由"),
            "accept_flg"=>array("label"=>"承認フラグ"),
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
            table("UserProduct")->deleteById($id);
        }
        return $this->redirect("id://admin_user_product.list", array("back"=>"1"));
    }
}
