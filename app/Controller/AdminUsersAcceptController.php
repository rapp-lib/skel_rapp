<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminUsersAcceptController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_users_accept.list",
        "search_table" => "User",
        "fields" => array(
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("id", "reg_date", "company_name", "name_kana"=>array(
                "last_name_kana ASC, first_name_kana ASC",
                "last_name_kana DESC, first_name_kana DESC",
            ))),
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
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("accept_flg","1")->select();
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_users_accept.form",
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
            "login_pw"=>array("label"=>"パスワード"),
            "zip"=>array("label"=>"郵便番号"),
            "pref"=>array("label"=>"都道府県"),
            "city"=>array("label"=>"市区郡町村"),
            "address"=>array("label"=>"番地"),
            "buildings"=>array("label"=>"建物名"),
            "tel"=>array("label"=>"電話番号"),
            "fax"=>array("label"=>"FAX番号"),
            "memo"=>array("label"=>"備考"),
            "admin_memo"=>array("label"=>"管理者備考"),
            "accept_flg"=>array("label"=>"承認フラグ"),
            "product_name" =>array("label"=>"型名（製品名）", "col_values_clause"=>false),
            "serial_number" =>array("label"=>"シリアルNo.", "col_values_clause"=>false),
            "purchase_source" =>array("label"=>"購入元", "col_values_clause"=>false),
            "purchase_reason" =>array("label"=>"購入理由", "col_values_clause"=>false),
        ),
        "rules" => array(
            "company_name",
            "department",
            "last_name",
            "first_name",
            "last_name_kana",
            "first_name_kana",
            array("mail", "required", "if"=>array("id"=>false)),
            array("login_pw", "required", "if"=>array("or"=>array(array("accept_flg"=>"1"),array("accept_flg"=>"2")))),
            "zip",
            "pref",
            "city",
            "tel",
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
                $t = $this->forms["entry"]->getTable()->findBy("accept_flg","1")->selectById($id);
                if ( ! $t) return $this->response("notfound");
                $this->forms["entry"]->setRecord($t);
                $this->forms["entry"]["product_name"] =$t["user_products"][0]["product_id_label"];
                $this->forms["entry"]["serial_number"] =$t["user_products"][0]["serial_number"];
                $this->forms["entry"]["purchase_source"] =$t["user_products"][0]["purchase_source"];
                $this->forms["entry"]["purchase_reason"] =$t["user_products"][0]["purchase_reason"];
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
        report($this->forms["entry"]["login_pw"]);
    }
    /**
     * @page
     */
    public function act_form_complete ()
    {
        $this->forms["entry"]->restore();
        if ( ! $this->forms["entry"]->isEmpty() && $this->forms["entry"]->isValid()) {
            $login_pw =$this->forms["entry"]["login_pw"];
            if ($this->forms["entry"]["accept_flg"] == "3") {
                // 削除処理
                table("User")->deleteById($this->forms["entry"]["id"]);
                $complete_flg = "delete";
            } else {
                // 更新処理
                $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
                if ($t["accept_flg"] == "2") {
                    table("User")->save(array(
                        "id" =>$t["id"],
                        "accept_date" =>date("Y-m-d H:i:s"),
                    ));
                    // ユーザ向け通知メールの送信
                    app("mailer")->send("mail://admin_users_accept.reply.html", array("t"=>$t, "login_pw"=>$login_pw), function($message){});
                }
                $complete_flg = "update";
            }
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://.list", array("back"=>"1","complete_flg"=>$complete_flg));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("User")->deleteById($id);
        }
        return $this->redirect("id://admin_users_accept.list", array("back"=>"1"));
    }
}
