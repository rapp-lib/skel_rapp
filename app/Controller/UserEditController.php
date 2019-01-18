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
            "company_name"=>array("label"=>"会社名"),
            "department"=>array("label"=>"部署"),
            "position"=>array("label"=>"役職"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"メール", "col_values_clause"=>false),
            "zip"=>array("label"=>"郵便番号"),
            "pref"=>array("label"=>"都道府県"),
            "city"=>array("label"=>"市区郡町村"),
            "address"=>array("label"=>"番地"),
            "buildings"=>array("label"=>"建物名"),
            "tel"=>array("label"=>"電話番号"),
            "fax"=>array("label"=>"FAX番号"),
            "login_pw"=>array("label"=>"パスワード"),
            // "login_pw_confirm"=>array("label"=>"パスワード確認", "col"=>false),
            "memo"=>array("label"=>"備考"),
        ),
        "rules" => array(
            array("login_pw", "required", "message"=>"パスワードを入力してください。"),
            // array("zip", "required", "message"=>"郵便番号は半角英数字で入力してください。"),
            // array("tel", "required", "message"=>"電話番号は半角英数字で入力してください。"),
            // array("fax", "required", "message"=>"FAX番号は半角英数字で入力してください。"),

            // array("login_pw_confirm", "required", "if"=>array("login_pw"=>true)),
            // array("login_pw_confirm", "confirm", "target_field"=>"login_pw"),
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
            $t = $this->forms["entry"]->getTableWithValues()->saveMine()->getSavedRecord();
            // 管理者通知メールの送信
            app("mailer")->send(array("text"=>"mail://user_edit.admin.html"), array("t"=>$t), function($message){});
            // 自動返信メールの送信
            app("mailer")->send("mail://user_edit.reply.html", array("t"=>$t), function($message){});
            $this->forms["entry"]->clear();
        }
    }
}
