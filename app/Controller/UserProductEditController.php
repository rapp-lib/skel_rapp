<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserProductEditController extends Controller_User
{
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "user_product_edit.form",
        "csrf_check" => true,
        "table" => "UserProduct",
        "fields" => array(
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
                return $this->redirect("id://.form_confirm");
            }
        } elseif ($this->input["back"]) {
            $this->forms["entry"]->restore();
        } else {
            $this->forms["entry"]->clear();
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
}
