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
     * 代理ログインフォーム
     */
    protected static $form_login = array(
        "form_page" => "admin_users.list",
        "fields" => array(
            "id",
        ),
        "rules" => array(
        ),
    );
    /**
     * @page
     */
    public function act_list ()
    {
        if ($this->forms["login"]->receive($this->input)) {
            if ($this->forms["login"]->isValid()) {
                $result = app()->user->authenticate("user", array(
                    "type" => "deputy",
                    "id" => $this->forms["login"]["id"],
                ));
                report($result);
                if ($result) {
                    return $this->redirect("id://user_products.list");
                } else {
                    return $this->redirect("id://admin_users.list", array("back"=>"1","complete_flg"=>"deputy_error"));
                }
            }
        }
        if ($this->input["back"]) {
            $this->forms["search"]->restore();
        } elseif ($this->forms["search"]->receive($this->input)) {
            $this->forms["search"]->save();
        }
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("accept_flg","2")->select();
        $this->vars["complete_flg"] = $this->input["complete_flg"];
        report(table("User")->getColNamesByAttr("personal_data"));
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
            array("mail", "required", "if"=>array("id"=>false)),
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
            // 個人情報に当たるカラムを空欄にしてから削除
            table("User")->setPersonalDelete($id)->save();
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
     * 代理ログインフォーム
     */
    protected static $form_csvdl_w = array(
        "form_page" => "admin_users.csvdl",
        "fields" => array(
            "w",
        ),
        "rules" => array(
        ),
    );
    /**
     * 代理ログインフォーム
     */
    protected static $form_csvdl_delete = array(
        "form_page" => "admin_users.csvdl",
        "fields" => array(
            "w",
        ),
        "rules" => array(
        ),
    );
    /**
     * @page
     */
    /**
     * @page
     */
    public function act_csvdl ()
    {
        $this->vars["week_list"] = table("User")->findBy("accept_flg","2")->fields(array(
            "accept_date",
            "download_flg",
            "erase_flg",
        ))->select()->getCsvdlList();
    }
    /**
     * CSV設定
     */
    protected static $form_csvdl_csv = array(
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
    public function act_csvdl_csv ()
    {
        // 検索結果の取得
        $w = $this->input["w"];
        $start_date =date("Y-m-d",strtotime(preg_replace('!^(\d{4})(\d{2})$!', "$1W$2", $w)."1 -1DAY"));
        $end_date = date("Y-m-d",strtotime(preg_replace('!^(\d{4})(\d{2})$!', "$1W$2", $w)."7 -1DAY"));
        $ts = table("User")->findBy("accept_flg","2")->findBy("download_flg","1")
            ->findBy("DATE_FORMAT(accept_date, '%Y-%m-%d') >= '".$start_date."'")
            ->findBy("DATE_FORMAT(accept_date, '%Y-%m-%d') <= '".$end_date."'")
            ->removePagenation()->select();
        $id_list = $ts->getHashedBy("id");
        table("User")->save(array(
            "download_flg" =>"2",
            "download_date" =>date("Y-m-d H:i:s"),
            table("User")->getIdColName() => $id_list,
        ));
        
        // CSVファイルの書き込み
        $csv = $this->forms["csvdl_csv"]->openCsvFile("php://temp", "w");
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
    public function act_csvdl_delete ()
    {
        // 検索結果の取得
        $w = $this->input["w"];
        $start_date =date("Y-m-d",strtotime(preg_replace('!^(\d{4})(\d{2})$!', "$1W$2", $w)."1 -1DAY"));
        $end_date = date("Y-m-d",strtotime(preg_replace('!^(\d{4})(\d{2})$!', "$1W$2", $w)."7 -1DAY"));
        $ts = table("User")->findBy("accept_flg","2")->findBy("download_flg","2")->findBy("erase_flg","1")
            ->findBy("DATE_FORMAT(accept_date, '%Y-%m-%d') >= '".$start_date."'")
            ->findBy("DATE_FORMAT(accept_date, '%Y-%m-%d') <= '".$end_date."'")
            ->removePagenation()->select();
        $id_list = $ts->getHashedBy("id");
        if (count((array)$id_list) > 0) {
            table("User")->setPersonalDelete($id_list)->save();
        }
        
        return $this->redirect("id://admin_users.csvdl", array("back"=>"1","complete_flg"=>"delete"));
    }
}
