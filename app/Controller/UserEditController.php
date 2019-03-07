<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserEditController extends Controller_User
{
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "user_edit.form",
        "csrf_check" => true,
        "table" => "User",
        "fields" => array(
            "company_name"=>array("label"=>"所属企業/団体名", "col_values_clause"=>false),
            "department"=>array("label"=>"部署名", "col_values_clause"=>false),
            "position"=>array("label"=>"役職名", "col_values_clause"=>false),
            "last_name"=>array("label"=>"氏名(姓)", "col_values_clause"=>false),
            "first_name"=>array("label"=>"氏名(名)", "col_values_clause"=>false),
            "last_name_kana"=>array("label"=>"氏名(セイ)", "col_values_clause"=>false),
            "first_name_kana"=>array("label"=>"氏名(メイ)", "col_values_clause"=>false),
            "mail"=>array("label"=>"E-Mail", "col_values_clause"=>false),
            "zip"=>array("label"=>"郵便番号", "col_values_clause"=>false),
            "pref"=>array("label"=>"都道府県", "col_values_clause"=>false),
            "city"=>array("label"=>"市区郡町村", "col_values_clause"=>false),
            "address"=>array("label"=>"番地", "col_values_clause"=>false),
            "buildings"=>array("label"=>"建物名", "col_values_clause"=>false),
            "tel"=>array("label"=>"電話番号", "col_values_clause"=>false),
            "fax"=>array("label"=>"FAX番号", "col_values_clause"=>false),
            "login_pw"=>array("label"=>"パスワード"),
            "login_pw_confirm"=>array("label"=>"パスワード確認", "col"=>false),
            "memo"=>array("label"=>"備考"),
        ),
        "rules" => array(
            array("last_name", '\R\App\Table\UserTable::multibyteCheck', "message"=>"氏名（氏）は全角で入力して下さい。"),
            array("first_name", '\R\App\Table\UserTable::multibyteCheck', "message"=>"氏名（名）は全角で入力して下さい。"),
            array("last_name_kana", "format", "format"=>"kana", "message"=>"カナ（シ）は全角カナのみで入力してください。"),
            array("first_name_kana", "format", "format"=>"kana", "message"=>"カナ（メイ）は全角カナのみで入力してください。"),
            array("login_pw", "format",  "format"=>"alphanum", "message"=>"パスワードは半角で入力して下さい。"),
            array("login_pw", "length", "min"=>8, "message"=>"パスワードは8文字以上で入力して下さい。"),
            array("login_pw_confirm", "required", "if"=>array("login_pw"=>true), "message"=>"パスワード確認を入力してください。"),
            array("login_pw_confirm", "confirm", "target_field"=>"login_pw", "message"=>"パスワード確認に入力された値が異なっています"),
            array("zip", "format", "format"=>"zip", "message"=>"郵便番号を正しい形式で入力してください。"),
            array("pref", "enum", "enum"=>"User.pref", "message"=>"都道府県の値が不正です"),
            array("tel", "format", "format"=>"tel", "message"=>"電話番号は半角で入力して下さい。"),
            array("tel", "length", "max"=>20, "message"=>"電話番号は20文字以下で入力して下さい。"),
            array("fax", "format", "format"=>"tel", "message"=>"FAX番号は半角で入力して下さい。"),
            array("fax", "length", "max"=>20, "message"=>"FAX番号は20文字以下で入力して下さい。"),
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
            $t = $this->forms["entry"]->getTable()->findMine()->selectOne();
            $this->forms["entry"]->setRecord($t);
            if ( ! $t) return $this->response("badrequest");
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
            $t = $this->forms["entry"]->getRecord();
            $this->forms["entry"]->getTableWithValues()->saveMine()->getSavedRecord();
            // 管理者通知メールの送信
            app("mailer")->send("mail://user_edit.admin.html", array("t"=>$t), function($message){});            
            // // 自動返信メールの送信
            // app("mailer")->send("mail://user_edit.reply.html", array("t"=>$t), function($message){});
            $this->forms["entry"]->clear();
        }
    }
}
