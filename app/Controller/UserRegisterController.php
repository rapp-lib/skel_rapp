<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserRegisterController extends Controller_Guest
{
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "user_register.form",
        "csrf_check" => true,
        "table" => "User",
        "fields" => array(
            "company_name"=>array("label"=>"会社名"),
            "department"=>array("label"=>"部署"),
            "position"=>array("label"=>"役職"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"メール"),
            "mail_confirm"=>array("label"=>"メール確認入力", "col"=>false),
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
            "products.*.ord_seq"=>array("col"=>false),
            "products.*.product_id"=>array("label"=>"製品ID"),
            "products.*.serial_number"=>array("label"=>"シリアルNo"),
            "products.*.purchase_source"=>array("label"=>"購入元"),
            "products.*.purchase_reason"=>array("label"=>"購入理由"),
            "products.*.accept_flg"=>array("label"=>"承認フラグ"),
            "products.*.accept_date"=>array("label"=>"承認通過日付"),
            "memo"=>array("label"=>"備考"),
            "deliv_flg"=>array("label"=>"配信"),
            "agree_flg"=>array("label"=>"ユーザー規約に同意", "col"=>false),
        ),
        "rules" => array(
            array("agree_flg", "required", "message"=>"ユーザー規約に同意が必要です。"),
            array("company_name", "required", "message"=>"所属企業/団体名を入力してください。"),
            array("department", "required", "message"=>"部署名を入力してください。"),
            array("last_name", "required", "message"=>"氏名（氏）を入力してください。"),
            array("first_name", "required", "message"=>"氏名（名）を入力してください。"),
            array("last_name_kana", "required", "message"=>"カナ（シ）を入力してください。"),
            array("first_name_kana", "required", "message"=>"カナ（メイ）を入力してください。"),
            array("mail", "required", "message"=>"E-mailを入力してください。"),
            array("mail_confirm", "required", "if"=>array("mail"=>true), "message"=>"確認用E-mailメールアドレスを入力してください。"),
            array("mail_confirm", "confirm", "target_field"=>"mail"),
            array("zip", "required", "message"=>"郵便番号を入力してください。"),
            array("pref", "required", "message"=>"都道府県を選択してください。"),
            array("city", "required", "message"=>"市区郡町村を入力してください。"),
            array("address", "required", "message"=>"番地を入力してください。"),
            array("tel", "required", "message"=>"電話番号を入力してください。"),
            array("products.*.product_id", "required", "message"=>"型名(製品名)を選択してください。"),
            array("products.*.serial_number", "required", "message"=>"シリアルNo.を入力してください。"),
            // array("login_pw", "required"),
            // array("login_pw_confirm", "required", "if"=>array("login_pw"=>true)),
            // array("login_pw_confirm", "confirm", "target_field"=>"login_pw"),
            // "products.*.serial_number",
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
            $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
            // 管理者通知メールの送信
            app("mailer")->send(array("text"=>"mail://user_register.admin.html"), array("t"=>$t), function($message){});
            // 自動返信メールの送信
            app("mailer")->send("mail://user_register.reply.html", array("t"=>$t), function($message){});
            $this->forms["entry"]->clear();
        }
    }
}
