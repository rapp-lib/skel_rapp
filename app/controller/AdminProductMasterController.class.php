<?php

/**
 * @controller
 */
class AdminProductMasterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $access_as = "admin";
    protected $priv_required = true;

    //TODO: type=での形式変換はLRA/UserFile系の機能の集約←Request実装後
    protected static $form_search = array(
        "pages" => array(".view_list", ".view_csv"),
        // 管理画面系の戻るリンクの動作のためにSessionを有効にすることも可能
        "session" => false,
        // pagesへのリンクにURL経由で全Valuesを渡すようにする指定
        "values_over_request" => true,
        // findBySearchFormができるようになる
        "table" => "Product",
        // 検索設定
        "list_setting" => array(
            "search" =>array(
                "name" =>array("type"=>'eq', "target"=>"name"),
                "category" =>array("type"=>'eq', "target"=>"category"),
                "open_date" =>array("type"=>'eq', "target"=>"open_date"),
            ),
            "sort" =>array("sort_param_name"=>"sort", "default"=>"id@ASC"),
            "paging" =>array("offset_param_name"=>"offset", "limit"=>20, "slider"=>10),
        ),
        /*
        // 旧list_settingにかわる新しい検索システムの設定
        "fields" => array(
            "freeword" => array("search"=>"word", "col"=>array("name")),
            "category" => array("search"=>"eq"),
            "open_date_start" => array("search"=>"date_range_start", "type"=>"split_date", "col"=>"open_date"),
            "open_date_end" => array("search"=>"date_range_end", "type"=>"split_date", "col"=>"open_date"),
            "p" => array("search"=>"page", "col"=>false),
            "order" => array("search"=>"sort", "col"=>false),
        ),
        */
    );
    protected static $form_entry = array(
        // 指定のページで自動的に読み込む
        "auto_restore" => true,
        "pages" => array(".entry_form", ".entry_confirm", ".entry_exec"),
        // findById/saveができるようになる
        "table" => "Product",
        // 使用可能な項目
        // Requestから値を取り込むとき、Inputに値を渡す時に変換をかける
        "fields" => array(
            "id",
            "name",
            "mail",
            "tel",
            "img",
            "mail_confirm" => array("col"=>false),
            "open_date",
            "main_img_file",
            "detail",
            "detail.name",
            "sub_img_files",
            "sub_img_files.*.title",
            "sub_img_files.*.img_file",
            "category",
        ),
        // 入力チェックの記述
        // 変換済みの値に対して入力チェック
        "rules" => array(
            "name",
            "contact",
            array("mail", "required", "if_target"=>"contact", "if_value"=>"mail"),
            array("mail", "format", "format"=>"mail"),
            array("mail_confirm", "required", "if_target"=>"mail"),
            array("mail_confirm", "confirm", "target"=>"mail"),
            "sub_img_files.*.title",
        ),
        // CSV入出力用の設定
        // "csv" => array(
        //     "file_charset" =>"SJIS-WIN",
        //     "data_charset" =>"UTF-8",
        //     "ignore_empty_line" =>true,
        // ),
    );
    protected static $form_entry_csv = array(
        "auto_restore" => true,
        "fields" => array(
            "csv_file",
        ),
        "rules" => array(
            "csv_file",
        ),
    );
    protected static $form_csv_line = array(
        // 暫定的に旧csv_setting同様の指定
        "rows" => array(
            "id" => "#ID",
            "name" => "名称",
            "img" => "写真",
            "category" => "カテゴリ",
            "open_date" => "公開日時",
        ),
        "filters" => array(
            array("filter"=>"sanitize"),
            array("target"=>"category", "filter"=>"list_select", "list"=>"product_category"),
            array("target"=>"open_date", "filter"=>"date"),
        ),
    );

    /**
     * @page
     * @title 製品管理 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 製品管理 一覧表示
     */
    public function act_view_list ()
    {
        $this->vars["ts"] = $this->forms["search"]->findBySearchForm()->select();
        $this->vars["p"] = $this->vars["ts"]->getPager();
    }

    /**
     * @page
     * @title 製品管理 入力フォーム
     */
    public function act_entry_form ()
    {
        if ($this->forms["entry"]->receive()) {
            $this->forms["entry"]->save();
            if ($this->forms["entry"]->isValid()) {
                redirect("page:.entry_exec");
            }
        } elseif ($id = $this->request["id"]) {
            $this->forms["entry"]->init($id);
        } elseif ( ! $this->request["back"]) {
            $this->forms["entry"]->clear();
        }
    }

    /**
     * @page
     * @title 製品管理 確認
     */
    public function act_entry_confirm ()
    {
    }

    /**
     * @page
     * @title 製品管理 完了
     */
    public function act_entry_exec ()
    {
        if ($this->forms["entry"]->isValid()) {
            $this->forms["entry"]->getRecord()->save();
            $this->forms["entry"]->clear();
        }
        redirect("page:.index");
    }

    /**
     * @page
     * @title 製品管理 削除
     */
    public function act_delete ()
    {
        if ($id = $this->request["id"]) {
            if ($t = table("Product")->selectById($id)) {
                $t->delete();
            }
        }
        redirect("page:.index");
    }

    /**
     * @page
     * @title 製品管理 CSVダウンロード
     */
    public function act_view_csv ()
    {
        set_time_limit(0);
        registry("Report.error_reporting",E_USER_ERROR|E_ERROR);
        //report()->setLogErrorOnly(true);

        $res =$this->forms["search"]->findBySearchForm()->selectNoFetch();

        // CSVファイルの書き込み準備
        $csv_filename =registry("Path.tmp_dir")
            ."/csv_output/Product-".date("Ymd-His")."-"
            .sprintf("%04d",rand(0,9999)).".csv";
        $csv =new CSVHandler($csv_filename,"w",$this->csv_setting);

        while ($t =$res->fetch()) {
            $csv->write_line($t);
        }

        // データ出力
        clean_output_shutdown(array(
            "download" =>basename($csv_filename),
            "file" =>$csv_filename,
        ));
    }

    /**
     * @page
     * @title CSVインポートフォーム
     */
    public function act_entry_csv_form ()
    {
        if ($this->forms["entry_csv"]->received()) {
            if ($this->forms["entry_csv"]->hasValidValues()) {
                redirect("page:.entry_csv_exec");
            }
        }
    }

    /**
     * @page
     * @title CSVインポート確認
     */
    public function act_entry_csv_confirm ()
    {
    }

    /**
     * @page
     * @title CSVインポート完了
     */
    public function act_entry_csv_exec ()
    {
        if ($this->forms["entry_csv"]->hasValidValues()) {
            $csv_filename =obj("UserFileManager")
                ->get_uploaded_file($this->forms["entry_csv"]["csv_file"], "private");
            $csv =new CSVHandler($csv_filename,"r",$this->csv_setting);
            // DBへの登録処理
            table("Product")->transactionBegin();
            while (($t=$csv->read_line()) !== null) {
                table("Product")->save($t);
            }
            table("Product")->transactionCommit();
        }
        redirect("page:.view_list");
    }
}