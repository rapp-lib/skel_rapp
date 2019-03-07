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
            array("Product", array("Product.id=UserProduct.product_id"), "RIGHT"),
        ),
        "fields" => array(
            "sort" => array("search"=>"sort", "cols"=>array("model")),
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
        $this->vars["complete_flg"] = $this->input["complete_flg"];
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
            "product_bracket_name"=>array("label"=>"型名（製品名）", "col"=>false),
            "product_name_id"=>array("label"=>"型名（製品名）プルダウン", "col"=>false),
            "product_id"=>array("label"=>"型名（製品名）ID"),
            "serial_number"=>array("label"=>"シリアルNo."),
            "purchase_source"=>array("label"=>"購入元"),
            "purchase_reason"=>array("label"=>"購入理由"),
        ),
        "rules" => array(
            array("product_id", "required", "message"=>"型名(製品名)を選択してください。"),
            array("product_id", "enum", "enum"=>"UserProduct.product", "message"=>"型名(製品名)の値が不正です"),
            array("serial_number", "required", "message"=>"シリアルNo.を入力してください。"),
            array("serial_number", "length", "max"=>10, "message"=>"シリアルNo.は10文字以下で入力して下さい。"),
            array("serial_number", "format", "format"=>"alphanum", "message"=>"シリアルNo.は半角で入力して下さい。"),
            array("purchase_source", "length", "max"=>100, "message"=>"購入元は100文字以下で入力して下さい。"),
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
        // 製品名のリストをアサイン
        foreach (app()->enum["UserProduct.product_bracket_name"] as $v) $product_bracket_name[] =$v;
        $this->vars["product_bracket_name_ts"] =json_encode($product_bracket_name,JSON_UNESCAPED_UNICODE);
        foreach (app()->enum["UserProduct.product_image"] as $k =>$v) $product_image[$k] =$v;
        $this->vars["product_image_ts"] =json_encode($product_image,JSON_UNESCAPED_UNICODE);
        foreach (app()->enum["UserProduct.product_name_id"] as $k =>$v) $product_name_id[$k] =$v;
        $this->vars["product_name_id_ts"] =json_encode($product_name_id,JSON_UNESCAPED_UNICODE);
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
            $t = $this->forms["entry"]->getTableWithValues()->setIgnoreAcceptFlg(true)->saveMine()->getSavedRecord();
            $t["bracket_name"]; // メール内で使用するために一度アクセスする。
            // 管理者通知メールの送信
            app("mailer")->send("mail://user_products.admin.html", array("t"=>$t), function($message){});
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
        return $this->redirect("id://user_products.list", array("back"=>"1","complete_flg"=>"delete"));
    }
}
