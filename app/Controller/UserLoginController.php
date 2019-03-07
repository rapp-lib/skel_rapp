<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserLoginController extends Controller_User
{
    /**
     * ログインフォーム
     */
    protected static $form_login = array(
        "form_page" => ".index",
        "fields" => array(
            "login_id",
            "login_pw",
            "redirect",
        ),
        "rules" => array(
        ),
    );
    /**
     * 更新情報検索フォーム
     */
    protected static $form_news_search = array(
        "receive_all" => true,
        "search_page" => "user_news.list",
        "search_table" => "News",
        "fields" => array(
            "p" => array("search"=>"page", "volume"=>10),
            "sort" => array("search"=>"sort", "cols"=>array("date DESC")),
        ),
    );
    /**
     * 注意事項検索フォーム
     */
    protected static $form_notice_search = array(
        "receive_all" => true,
        "search_page" => "user_notices.list",
        "search_table" => "Notice",
        "fields" => array(
            "sort" => array("search"=>"sort", "cols"=>array("number ASC")),
        ),
    );
    /**
     * @page
     */
    public function act_login ()
    {
        // ログインフォーム
        if ($this->forms["login"]->receive($this->input)) {
            if ($this->forms["login"]->isValid()) {
                // ログイン処理
                $result = app()->user->authenticate("user", array(
                    "type" => "idpw",
                    "login_id" => $this->forms["login"]["login_id"],
                    "login_pw" => $this->forms["login"]["login_pw"],
                ));
                if ($result) {
                    table("User")->values(array("last_login_date"=>date("Y-m-d H:i:s")))->saveMine();
                    return $this->redirect($this->forms["login"]["redirect"] ?: "id://user_products.list");
                } else {
                    $this->vars["login_error"] = true;
                }
            }
        // 転送先の設定
        } elseif ($redirect = $this->input["redirect"]) {
            $this->forms["login"]["redirect"] = $redirect;
        }

        // 更新情報
        $this->vars["news_ts"] = $this->forms["news_search"]->search()->findBy("date <= CURRENT_DATE")->select();

        // 注意事項
        $this->vars["notice_ts"] = $this->forms["notice_search"]->search()->select();
    }
    /**
     * @page
     */
    public function act_login_exit ()
    {
        // ログアウト処理
        app()->user->setPriv("user",false);
        // ログアウト後の転送処理
        // return $this->redirect("id://.login");
    }
    /**
     * リマインダーフォーム
     */
    protected static $form_entry = array(
        "form_page" => "user_login.reminder",
        "fields" => array(
            "mail"=>array("label"=>"メール"),
        ),
        "rules" => array(
            "mail",
            array("mail", "format", "format"=>"mail"),
            array("mail", "\R\App\Controller\UserLoginController::acceptedRegistered", "table"=>"User", "col_name"=>"mail"),
        ),
    );
    /**
     * @page
     */
    public function act_reminder ()
    {
        if ($this->forms["entry"]->receive($this->input)) {
            if ($this->forms["entry"]->isValid()) {
                $this->forms["entry"]->save();
                return $this->redirect("id://.reminder_send");
            }
        } else {
            $this->forms["entry"]->clear();
        }
    }
    /**
     * @page
     */
    public function act_reminder_send ()
    {
        $this->forms["entry"]->restore();
        if ( ! $this->forms["entry"]->isEmpty()) {
            // Credの発行
            $t = (array)$this->forms["entry"];
            $cred = app()->cache("cred")->createCred($t);
            $ttl = app()->cache("cred")->getTTL();
            // URL通知メールの送信
            $uri = $this->uri("id://.reminder_reset", array("cred"=>$cred));
            app("mailer")->send("mail://user_login.mailcheck.html", array("t"=>$t, "uri"=>$uri, "ttl"=>$ttl), function($message){});
            $this->forms["entry"]->clear();
        }
    }
    /**
     * PW入力フォーム
     */
    protected static $form_reset = array(
        "form_page" => "user_login.reminder_reset",
        "fields" => array(
            "cred"=>array("col"=>false),
            "login_pw"=>array("label"=>"パスワード"),
            "login_pw_confirm"=>array("label"=>"パスワード確認", "col"=>false),
        ),
        "rules" => array(
            "cred",
            "login_pw",
            array("login_pw", "format",  "format"=>"alphanum", "message"=>"パスワードは半角で入力して下さい。"),
            array("login_pw", "length", "min"=>8, "message"=>"パスワードは8文字以上で入力して下さい。"),
            array("login_pw_confirm", "required", "if"=>array("login_pw"=>true)),
            array("login_pw_confirm", "confirm", "target_field"=>"login_pw"),
        ),
    );
    /**
     * @page
     */
    public function act_reminder_reset ()
    {
        // credの承認ステータスチェック
        if ($this->forms["reset"]->receive($this->input)) {
            if ($this->forms["reset"]->isValid()) {
                $this->forms["reset"]->save();
                return $this->redirect("id://.reminder_complete");
            }
        } else {
            $this->forms["reset"]->clear();
            $this->forms["reset"]["cred"] = $this->input["cred"];
        }
        $cred_data = app()->cache("cred")->readCred($this->forms["reset"]["cred"]);
        if (table("User")->findBy("mail", $cred_data["mail"])->findBy("accept_flg", "2")->selectCount() == 0) {
            return $this->response("badrequest");
        }
        $this->vars["cred_data"] = $cred_data;
    }
    /**
     * @page
     */
    public function act_reminder_complete ()
    {
        $this->forms["reset"]->restore();
        if ( ! $this->forms["reset"]->isEmpty()) {
            // Credの解決
            $cred = $this->forms["reset"]["cred"];
            $cred_data = app()->cache("cred")->readCred($cred);
            // パスワードの更新
            $t = table("User")->findBy("mail", $cred_data["mail"])->findBy("accept_flg", "2")->selectOne();
            $this->vars["t"] = $t;
            table("User")->updateById($t["id"], array(
                "login_pw" => $this->forms["reset"]["login_pw"]
            ));
            app()->cache("cred")->dropCred($cred);
            $this->forms["reset"]->clear();
        }
    }
    /**
     * 認証済み登録チェック
     *  table : （必須）テーブル名
     *  col_name : （必須）カラム名
     */
    public function acceptedRegistered ($validator, $value, $rule)
    {
        if (! $value) return false;
        $q = table($rule["table"]);
        $q = $q->findBy("accept_flg", "2");
        $q = $q->findBy($rule["col_name"], $value);
        if (count($q->select())==1) return false;
        return array("message"=>___("登録されていません"));
    }
}
