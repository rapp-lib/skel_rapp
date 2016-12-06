<?php

namespace R\App\Controller;

/**
 * @controller
 */
class AdminMemberMasterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "admin";
    protected static $priv_required = true;

    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "search_page" => ".view_list",
        "search_table" => "Member",
        "fields" => array(
            "freeword" => array("search"=>"word", "target_col"=>array("name","nickname","mail",)),
            "p" => array("search"=>"page", "volume"=>20),
            "order" => array("search"=>"sort", "default"=>"id@ASC"),
        ),
    );

    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "auto_restore" => true,
        "form_page" => ".entry_form",
        "table" => "Member",
        "fields" => array(
            "id",
            "name",
            "nickname",
            "mail",
            "gender",
            "birthday",
            "job",
            "interest",
            "login_id",
            "login_pw",
        ),
        "rules" => array(
            "name", "nickname", "mail", "gender", "login_id",
            array("mail", "format", "format"=>"mail"),
            array("login_pw", "length", "min"=>6, "max"=>12),
        ),
    );

    /**
     * CSVアップロードフォーム
     */
    protected static $form_entry_csv = array(
        "auto_restore" => true,
        "fields" => array(
            "csv_file",
        ),
        "rules" => array(
            "csv_file",
        ),
    );

    /**
     * CSV設定
     */
    protected $csv_setting = array(
        "file_charset" =>"SJIS-WIN",
        "data_charset" =>"UTF-8",
        "rows" =>array(
            "id" =>"#ID",
            "name" =>"氏名",
            "nickname" =>"ニックネーム",
            "mail" =>"メールアドレス",
            "gender" =>"性別",
            "birthday" =>"生年月日",
            "job" =>"職業",
            "interest" =>"興味関心",
            "login_id" =>"ログインID",
            "login_pw" =>"パスワード",
        ),
        "filters" =>array(
            array("filter" =>"sanitize"),
            array("target" =>"gender",
                    "filter" =>"list_select",
                    "enum" =>"Member.gender"),
            array("target" =>"birthday",
                    "filter" =>"date"),
            array("target" =>"job",
                    "filter" =>"list_select",
                    "delim" =>"/",
                    "enum" =>"Member.job"),
            array("target" =>"interest",
                    "filter" =>"list_select",
                    "delim" =>"/",
                    "enum" =>"Member.interest"),
        ),
        "ignore_empty_line" =>true,
    );

    /**
     * @page
     * @title 会員管理 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 会員管理 一覧表示
     */
    public function act_view_list ()
    {
        if ($this->forms["search"]->receive()) {
            $this->forms["search"]->save();
        } elseif ($this->request["back"]) {
            $this->forms["search"]->restore();
        }
        $this->vars["ts"] = $this->forms["search"]->search()->select();

        report($this->forms["search"]);
    }

    /**
     * @page
     * @title 会員管理 入力フォーム
     */
    public function act_entry_form ()
    {
        if ($this->forms["entry"]->receive()) {
            if ($this->forms["entry"]->isValid()) {
                $this->forms["entry"]->save();
                redirect("page:.entry_confirm");
            }
        } elseif ($id = $this->request["id"]) {
            $this->forms["entry"]->init($id);
        } elseif ( ! $this->request["back"]) {
            $this->forms["entry"]->clear();
        }
    }

    /**
     * @page
     * @title 会員管理 確認
     */
    public function act_entry_confirm ()
    {
        redirect("page:.entry_exec");
    }

    /**
     * @page
     * @title 会員管理 完了
     */
    public function act_entry_exec ()
    {
        if ( ! $this->forms["entry"]->isEmpty()) {
            if ( ! $this->forms["entry"]->isValid()) {
                redirect("page:.entry_form", array("back"=>"1"));
            }
            $this->forms["entry"]->getRecord()->save();
            $this->forms["entry"]->clear();
        }
        redirect("page:.view_list", array("back"=>"1"));
    }

    /**
     * @page
     * @title 会員管理 削除
     */
    public function act_delete ()
    {
        if ($id = $this->request["id"]) {
            table("Member")->deleteById($id);
        }
        redirect("page:.view_list", array("back"=>"1"));
    }

    /**
     * @page
     * @title 会員管理 CSVダウンロード
     */
    public function act_view_csv ()
    {
        // 検索結果の取得
        $this->forms["search"]->restore();
        $res =$this->forms["search"]
            ->search()
            ->removePagenation()
            ->selectNoFetch();
        // CSVファイルの書き込み
        $csv_file = file_storage()->create("tmp");
        $csv = util("CSVHandler",array($csv_file->getFile(),"w",$this->csv_setting));
        while ($t = $res->fetch()) {
            $csv->write_line($t);
        }
        // データ出力
        response()->output(array(
            "download" => "export-".date("Ymd-his").".csv",
            "stored_file" => $csv_file,
        ));
    }

    /**
     * @page
     * @title CSVインポートフォーム
     */
    public function act_entry_csv_form ()
    {
        if ($this->forms["entry_csv"]->receive()) {
            if ($this->forms["entry_csv"]->isValid()) {
                $this->forms["entry_csv"]->save();
                redirect("page:.entry_csv_confirm");
            }
        } elseif ( ! $this->request["back"]) {
            $this->forms["entry"]->clear();
        }
    }

    /**
     * @page
     * @title CSVインポート確認
     */
    public function act_entry_csv_confirm ()
    {
        redirect('page:.entry_csv_exec');
    }

    /**
     * @page
     * @title CSVインポート完了
     */
    public function act_entry_csv_exec ()
    {
        if ( ! $this->forms["entry_csv"]->isEmpty()) {
            if ( ! $this->forms["entry_csv"]->isValid()) {
                redirect("page:.entry_csv_form", array("back"=>"1"));
            }
            // CSVファイルを開く
            $csv_file = file_storage()->get($this->forms["entry_csv"]["csv_file"]);
            $csv = util("CSVHandler", array($csv_file->getFile(),"r",$this->csv_setting));
            // DBへの登録処理
            table("Member")->transactionBegin();
            while ($t=$csv->read_line()) {
                table("Member")->save($t);
            }
            table("Member")->transactionCommit();
            $this->forms["entry_csv"]->clear();
        }
        redirect("page:.view_list", array("back"=>"1"));
    }
}
