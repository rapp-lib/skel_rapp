<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminUserController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_user.list",
        "search_table" => "User",
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
        "form_page" => "admin_user.form",
        "csrf_check" => true,
        "table" => "User",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "company_name"=>array("label"=>"会社名"),
            "department"=>array("label"=>"部署"),
            "position"=>array("label"=>"役職"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"メール"),
            "mail_confirm"=>array("label"=>"メール", "col"=>false),
            "zip"=>array("label"=>"郵便番号"),
            "pref"=>array("label"=>"都道府県"),
            "city"=>array("label"=>"市区郡町村"),
            "address"=>array("label"=>"番地"),
            "buildings"=>array("label"=>"建物名"),
            "tel"=>array("label"=>"電話番号"),
            "fax"=>array("label"=>"FAX番号"),
            "login_pw"=>array("label"=>"パスワード"),
            "login_pw_confirm"=>array("label"=>"パスワード確認", "col"=>false),
            "products"=>array("label"=>"ユーザー製品"),
            "products.*.id",
            "products.*.ord_seq"=>array("col"=>false),
            "products.*.product_id"=>array("label"=>"製品ID"),
            "products.*.model"=>array("label"=>"型名（製造名）"),
            "products.*.serial_number"=>array("label"=>"シリアルNo"),
            "products.*.purchase_source"=>array("label"=>"購入元"),
            "products.*.purchase_reason"=>array("label"=>"購入理由"),
            "memo"=>array("label"=>"備考"),
        ),
        "rules" => array(
            "company_name",
            "department",
            "position",
            "last_name",
            "first_name",
            "last_name_kana",
            "first_name_kana",
            array("mail", "required", "if"=>array("id"=>false)),
            array("mail_confirm", "required", "if"=>array("mail"=>true)),
            array("mail_confirm", "format", "format"=>"mail"),
            array("mail_confirm", "confirm", "target_field"=>"mail"),
            "zip",
            "pref",
            "city",
            "tel",
            array("login_pw", "required", "if"=>array("id"=>false)),
            array("login_pw_confirm", "required", "if"=>array("login_pw"=>true)),
            array("login_pw_confirm", "confirm", "target_field"=>"login_pw"),
            "products.*.serial_number",
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
            table("User")->deleteById($id);
        }
        return $this->redirect("id://admin_user.list", array("back"=>"1"));
    }
}
