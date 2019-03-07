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
            "company_name"=>array("label"=>"所属企業/団体名"),
            "department"=>array("label"=>"部署名"),
            "position"=>array("label"=>"役職名"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"E-Mail"),
            "mail_confirm"=>array("label"=>"E-Mail(確認用)", "col"=>false),
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
            "product_bracket_name"=>array("label"=>"型名（製品名）", "col"=>false),
            "product_name_id"=>array("label"=>"型名（製品名）プルダウン", "col"=>false),
            "products.*.ord_seq"=>array("col"=>false),
            "products.*.product_id"=>array("label"=>"型名（製品名）"),
            "products.*.serial_number"=>array("label"=>"シリアルNo."),
            "products.*.purchase_source"=>array("label"=>"購入元"),
            "products.*.purchase_reason"=>array("label"=>"購入理由"),
            "products.*.accept_flg"=>array("label"=>"承認ステータス"),
            "products.*.accept_date"=>array("label"=>"承認通過日付"),
            "memo"=>array("label"=>"備考"),
            "deliv_flg"=>array("label"=>"配信"),
            "agree_flg"=>array("label"=>"ユーザー規約に同意", "col"=>false),
        ),
        "rules" => array(
            array("agree_flg", "required", "message"=>"ユーザー規約に同意が必要です。"),
            array("agree_flg", "range", "min"=>"1", "max"=>"1", "message"=>"ユーザー規約に同意が必要です。"),
            array("company_name", "required", "message"=>"所属企業/団体名を入力してください。"),
            array("department", "required", "message"=>"部署名を入力してください。"),
            array("last_name", "required", "message"=>"氏名（氏）を入力してください。"),
            array("last_name", '\R\App\Table\UserTable::multibyteCheck', "message"=>"氏名（氏）は全角で入力して下さい。"),
            array("first_name", "required", "message"=>"氏名（名）を入力してください。"),
            array("first_name", '\R\App\Table\UserTable::multibyteCheck', "message"=>"氏名（名）は全角で入力して下さい。"),
            array("last_name_kana", "required", "message"=>"カナ（シ）を入力してください。"),
            array("first_name_kana", "required", "message"=>"カナ（メイ）を入力してください。"),
            array("last_name_kana", "format", "format"=>"kana", "message"=>"カナ（シ）は全角カナのみで入力してください。"),
            array("first_name_kana", "format", "format"=>"kana", "message"=>"カナ（メイ）は全角カナのみで入力してください。"),
            array("mail", "required", "message"=>"E-Mailを入力してください。"),
            array("mail", "format", "format"=>"mail", "message"=>"E-Mailを正しい形式で入力してください。"),
            array(
                "mail",
                "\R\App\Table\UserTable::duplicateAcceptFlgCheck",
                "id_role" =>"user",
                "message"=>"E-Mailは既に登録されています。",
            ),
            array("mail_confirm", "required", "if"=>array("mail"=>true), "message"=>"確認用E-Mailを入力してください。"),
            array("mail_confirm", "confirm", "target_field"=>"mail", "message"=>"確認用E-Mailに入力された値が異なっています"),
            array("zip", "required", "message"=>"郵便番号を入力してください。"),
            array("zip", "format", "format"=>"zip", "message"=>"郵便番号を正しい形式で入力してください。"),
            array("pref", "required", "message"=>"都道府県を選択してください。"),
            array("pref", "enum", "enum"=>"User.pref", "message"=>"都道府県の値が不正です"),
            array("city", "required", "message"=>"市区郡町村を入力してください。"),
            array("address", "required", "message"=>"番地を入力してください。"),
            array("tel", "required", "message"=>"電話番号を入力してください。"),
            array("tel", "format", "format"=>"tel", "message"=>"電話番号は半角で入力して下さい。"),
            array("tel", "length", "max"=>20, "message"=>"電話番号は20文字以下で入力して下さい。"),
            array("fax", "format", "format"=>"tel", "message"=>"FAX番号は半角で入力して下さい。"),
            array("fax", "length", "max"=>20, "message"=>"FAX番号は20文字以下で入力して下さい。"),
            array("products.*.product_id", "required", "message"=>"型名(製品名)を選択してください。"),
            array("products.*.serial_number", "required", "message"=>"シリアルNo.を入力してください。"),
            array("products.*.serial_number", "length", "max"=>10, "message"=>"シリアルNo.は10文字以下で入力して下さい。"),
            array("products.*.serial_number", "format", "format"=>"alphanum", "message"=>"シリアルNo.は半角で入力して下さい。"),
            array("products.*.purchase_source", "length", "max"=>100, "message"=>"購入元は100文字以下で入力して下さい。"),
            array("deliv_flg", "enum", "enum"=>"User.deliv_flg", "message"=>"配信の値が不正です"),
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
            $t = $this->forms["entry"]->getTableWithValues()->setIgnoreAcceptFlg(true)->save()->getSavedRecord();
            // 管理者通知メールの送信
            app("mailer")->send("mail://user_register.admin.html", array("t"=>$t), function($message){});
            $this->forms["entry"]->clear();
        }
    }
}
