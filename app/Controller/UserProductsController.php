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
        "search_joins" => array(
            array("Product", array("Product.id=UserProduct.product_id")),
        ),
        "fields" => array(
            "sort" => array("search"=>"sort", "cols"=>array("Product.model ASC")),
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
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("accept_flg","2")->findMine()->select();
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
            array("product_id", "required", "message"=>"型名(製品名)を選択してください。"),
            array("serial_number", "required", "message"=>"シリアルNo.を入力してください。"),
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
