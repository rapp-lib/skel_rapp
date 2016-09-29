<?php

/**
 * @controller
 */
class ProductMasterController extends Controller_App
{
    /**
     * 認証設定
     */
    protected $login_as = admin;
    protected $login_required = true;

    /**
     * 検索フォーム設定
     */
    protected $list_setting =array(
        "search" =>array(
            "name" =>array(
                    "type" =>'eq',
                    "target" =>"name"),
            "category" =>array(
                    "type" =>'eq',
                    "target" =>"category"),
            "open_date" =>array(
                    "type" =>'eq',
                    "target" =>"open_date"),
        ),
        "sort" =>array(
            "sort_param_name" =>"sort",
            "default" =>"id@ASC",
        ),
        "paging" =>array(
            "offset_param_name" =>"offset",
            "limit" =>20,
            "slider" =>10,
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
            "name" =>"名称",
            "img" =>"写真",
            "category" =>"カテゴリ",
            "open_date" =>"公開日時",
        ),
        "filters" =>array(
            array("filter" =>"sanitize"),
            array("target" =>"category",
                    "filter" =>"list_select",
                    "list" =>"product_category"),
            array("filter" =>"validate",
                    "required" =>array(),
                    "rules" =>array()),
        ),
        "ignore_empty_line" =>true,
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
        $this->context("c",1);

        if ($_REQUEST["_i"]=="c") {
            $this->c->clear();
            $this->c->input($_REQUEST);
        }

        list($this->vars["ts"] ,$this->vars["p"]) =
            model("Product")->get_by_search_form($this->list_setting, $this->c->input());
    }

    /**
     * @page
     * @title 製品管理 入力フォーム
     */
    public function act_entry_form ()
    {
        $this->context("c",1,true);

        // 入力値のチェック
        if ($_REQUEST["_i"]=="c") {
            $this->c->validate_input($_REQUEST,array(
            ));

            if ($this->c->has_valid_input()) {
                redirect("page:.entry_confirm");
            }
        }

        // id指定があれば既存のデータを読み込む
        if ($_REQUEST["id"]) {
            $this->c->id($_REQUEST["id"]);
            $t =model("Product")->get_by_id($this->c->id());

            if ( ! $t) {
                $this->c->id(false);
                redirect("page:.view_list");
            }

            $this->c->input($t);
        }
    }

    /**
     * @page
     * @title 製品管理 確認
     */
    public function act_entry_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();

        redirect("page:.entry_exec");
    }

    /**
     * @page
     * @title 製品管理 完了
     */
    public function act_entry_exec ()
    {
        $this->context("c",1,true);
        if ($this->c->has_valid_input()) {
            // データの記録
            $fields =$this->c->get_fields(array(
                "name",
                "img",
                "category",
                "open_date",
            ));
            model("Product")->save($fields,$this->c->id());

            $this->c->clear();
        }

        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 製品管理 削除
     */
    public function act_delete ()
    {
        $this->context("c");

        // idの指定
        $this->c->id($_REQUEST["id"]);

        // 既存のデータを確認
        $t =model("Product")->get_by_id($this->c->id());

        if ( ! $t) {
            redirect("page:.view_list");
        }

        // データの削除
        model("Product")->drop($this->c->id());

        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 製品管理 CSVダウンロード
     */
    public function act_view_csv ()
    {
        set_time_limit(0);
        registry("Report.error_reporting",E_USER_ERROR|E_ERROR);

        $this->context("c",1);

        $res =model("Product")                ->get_by_search_form($this->list_setting,$this->c->input(),true);

        // CSVファイルの書き込み準備
        $csv_filename =registry("Path.tmp_dir")
                ."/csv_output/Product-"
                .date("Ymd-His")."-"
                .sprintf("%04d",rand(0,9999)).".csv";
        $csv =new CSVHandler($csv_filename,"w",$this->csv_setting);

        while (($t =$res->fetch()) !== null) {
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
        $this->context("c",1,true);

        // 入力値のチェック
        if ($_REQUEST["_i"]=="c") {
            $this->c->validate_input($_REQUEST,array(
                "csv_file",
            ));

            if ($this->c->has_valid_input()) {
                redirect("page:.entry_csv_confirm");
            }
        }
    }

    /**
     * @page
     * @title CSVインポート確認
     */
    public function act_entry_csv_confirm ()
    {
        $this->context("c",1,true);
        $this->vars["t"] =$this->c->get_valid_input();

        redirect('page:.entry_csv_exec');
    }

    /**
     * @page
     * @title CSVインポート完了
     */
    public function act_entry_csv_exec ()
    {
        set_time_limit(0);
        registry("Report.error_reporting",E_USER_ERROR|E_ERROR);

        $this->context("c",1,true);

        $csv_filename =obj("UserFileManager")->get_uploaded_file(
                $this->c->input("csv_file"), "private");

        // CSVファイルの読み込み準備
        $csv =new CSVHandler($csv_filename,"r",$this->csv_setting);

        dbi()->begin();

        while (($t=$csv->read_line()) !== null) {

            // CSVフォーマットエラー
            if ($errors =$csv->get_errors()) {

                dbi()->rollback();

                $this->c->errors("Import.csv_file",$errors);

                redirect("page:.entry_csv_form");
            }

            // DBへの登録
            $c_import =new Context_App;
            $c_import->id($t["id"]);
            $c_import->input($t);

            $keys =array_keys($this->csv_setting["rows"]);
            $fields =$c_import->get_fields($keys);

            model("Product")->save($fields,$c_import->id());
        }

        dbi()->commit();

        redirect("page:.view_list");
    }
}