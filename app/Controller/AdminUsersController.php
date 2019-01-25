<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminUsersController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_users.list",
        "search_table" => "User",
        "fields" => array(
            "mail"=>array("search"=>"word", "target_col"=>"mail"),
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("id", "reg_date")),
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
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("accept_flg","2")->select();
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * @page
     */
    public function act_detail ()
    {
        $this->vars["t"] = table("User")->selectById($this->input["id"]);
        if ( ! $this->vars["t"]) return $this->response("notfound");
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_users.form",
        "csrf_check" => true,
        "table" => "User",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "company_name"=>array("label"=>"所属企業/団体名"),
            "department"=>array("label"=>"部署名"),
            "position"=>array("label"=>"役職名"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"E-Mail"),
            "zip"=>array("label"=>"郵便番号"),
            "pref"=>array("label"=>"都道府県"),
            "city"=>array("label"=>"市区郡町村"),
            "address"=>array("label"=>"番地"),
            "buildings"=>array("label"=>"建物名"),
            "tel"=>array("label"=>"電話番号"),
            "fax"=>array("label"=>"FAX番号"),
            "login_pw"=>array("label"=>"パスワード"),
            "login_pw_confirm"=>array("label"=>"パスワード確認", "col"=>false),
            "memo"=>array("label"=>"備考"),
            "admin_memo"=>array("label"=>"管理者備考"),
            "last_login_date"=>array("label"=>"最終ログイン日時", "col_values_clause"=>false),
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
            "zip",
            "pref",
            "city",
            "tel",
            array("login_pw", "required", "if"=>array("id"=>false)),
            array("login_pw_confirm", "required", "if"=>array("login_pw"=>true)),
            array("login_pw_confirm", "confirm", "target_field"=>"login_pw"),
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
        return $this->redirect("id://.detail", array("id"=>$t["id"],"complete_flg"=>"update"));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("User")->deleteById($id);
        }
        return $this->redirect("id://admin_users.list", array("back"=>"1","complete_flg"=>"delete"));
    }
    /**
     * CSV設定
     */
    protected static $form_csv = array(
        "table" => "User",
        "fields" => array(
            "id"=>array("label"=>"#ID"),
            "company_name"=>array("label"=>"所属企業/団体名"),
            "department"=>array("label"=>"部署名"),
            "position"=>array("label"=>"役職名"),
            "last_name"=>array("label"=>"氏名(姓)"),
            "first_name"=>array("label"=>"氏名(名)"),
            "last_name_kana"=>array("label"=>"氏名(セイ)"),
            "first_name_kana"=>array("label"=>"氏名(メイ)"),
            "mail"=>array("label"=>"E-Mail"),
            "zip"=>array("label"=>"郵便番号"),
            "pref"=>array("label"=>"都道府県"),
            "city"=>array("label"=>"市区郡町村"),
            "address"=>array("label"=>"番地"),
            "buildings"=>array("label"=>"建物名"),
            "tel"=>array("label"=>"電話番号"),
            "fax"=>array("label"=>"FAX番号"),
            "login_pw"=>array("label"=>"パスワード"),
            "memo"=>array("label"=>"備考"),
            "admin_memo"=>array("label"=>"管理者備考"),
        ),
        "rules" => array(
            "company_name",
            "department",
            "position",
            "last_name",
            "first_name",
            "last_name_kana",
            "first_name_kana",
            array("mail", "required"),
            "zip",
            "pref",
            "city",
            "tel",
            array("login_pw", "required"),
        ),
        "csv_setting" => array(
            "ignore_empty_line" => true,
            "rows" => array(),
            "filters" => array(
                array("pref", "enum_value", "enum"=>"User.pref"),
            ),
        ),
    );
    /**
     * @page
     */
    public function act_csv ()
    {
        // 検索結果の取得
        $this->forms["search"]->restore();
        $ts = $this->forms["search"]->search()->findBy("accept_flg","2")->removePagenation()->select();
        // CSVファイルの書き込み
        $csv = $this->forms["csv"]->openCsvFile("php://temp", "w");
        foreach ($ts as $t) $csv->writeRecord($t);
        // データ出力
        return app()->http->response("stream", $csv->getHandle(), array("headers"=>array(
            'content-type' => 'application/octet-stream',
            'content-disposition' => 'attachment; filename='.'User.csv'
        )));
    }
    /**
     * @page
     */
    public function act_csvdl ()
    {
        if ($this->input["back"]) {
            $this->forms["search"]->restore();
        } elseif ($this->forms["search"]->receive($this->input)) {
            $this->forms["search"]->save();
        }
        //$this->vars["ts"] = $this->forms["search"]->search()->findBy("accept_flg","2")->select();
        table("User")->findBy("accept_flg","2")->select(array(
                "download_flg",
                "erase_flg",
            ));
        // 週ごとにDLフラグや抹消フラグを集計する必要がある
        // 参考SQL：
        // select id, accept_date - interval date_format(accept_date,'%w') day as w from User
    }
}
