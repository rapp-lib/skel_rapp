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
        "search_page" => ".view_list",
        "pages" => array(".view_list", ".view_csv"),
        "search_table" => "Product",
        "fields" => array(
            "freeword" => array("search"=>"word", "target_col"=>array("name")),
            "category" => array("search"=>"where", "target_col"=>"category"),
            "reg_date_start" => array("search"=>"where", "target_col"=>"reg_date <"),
            "reg_date_end" => array("search"=>"where", "target_col"=>"reg_date >="),
            "p" => array("search"=>"page", "volume"=>3),
            "order" => array("search"=>"sort", "default"=>"id@ASC"),
        ),
    );
    protected static $form_entry = array(
        "auto_restore" => true,
        "pages" => array(".entry_form", ".entry_confirm", ".entry_exec"),
        "table" => "Product",
        "fields" => array(
            "id",
            "name",
            "img",
            "category",
            "mail_confirm" => array("col"=>false),
            "open_date",
            /*
            "detail",
            "detail.name",
            "sub_img_files",
            "sub_img_files.*.img_file",
            */
        ),
        // 入力チェックの記述
        // 変換済みの値に対して入力チェック
        "rules" => array(
            "name",
            array("category", "required", "if_target"=>"name"),
            //array("mail", "format", "format"=>"mail"),
            //array("mail_confirm", "required", "if_target"=>"mail"),
            //array("mail_confirm", "confirm", "target"=>"mail"),
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
        if ($this->forms["search"]->receive()) {
            $this->forms["search"]->save();
        } elseif ($this->request["back"]) {
            $this->forms["search"]->restore();
        }
        $this->vars["ts"] = $this->forms["search"]->search()->select();
    }

    /**
     * @page
     * @title 製品管理 入力フォーム
     */
    public function act_entry_form ()
    {
        if ($this->forms["entry"]->receive()) {
            if ($this->forms["entry"]->isValid()) {
                $this->forms["entry"]->save();
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
        if ( ! $this->forms["entry"]->isEmpty()) {
            if ( ! $this->forms["entry"]->isValid()) {
                redirect("page:.entry_form");
            }
            $this->forms["entry"]->getRecord()->save();
            $this->forms["entry"]->clear();
        }
        redirect("page:.view_list",array("back"=>"1"));
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
        $csv =new CSVHandler($csv_filename,"w",static::$form_csv_line);

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
            $csv =new CSVHandler($csv_filename,"r",static::$form_csv_line);
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