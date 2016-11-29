<?php

namespace R\App\Controller;

/**
 * @controller
 */
class AdminPostMasterController extends Controller_App
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
        "search_table" => "Post",
        "fields" => array(
            "freeword" => array("search"=>"word", "target_col"=>array("title","content","youtube_id",)),
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
        "table" => "Post",
        "fields" => array(
            "id",
            "title",
            "content",
            "youtube_id",
            "discovery_rank",
            "keyword",
            "description",
            "category",
            "thumbnail_img" => array("input_convert"=>"file_upload", "storage"=>"public"),
            "display_date",
            "display_end_date",
        ),
        "rules" => array(
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
            "title" =>"タイトル",
            "content" =>"本文",
            "youtube_id" =>"youtubeID",
            "discovery_rank" =>"DISCOVERY枠順位",
            "keyword" =>"META KEYWORD",
            "description" =>"META DESCRIPTION",
            "category" =>"カテゴリー",
            "thumbnail_img" =>"サムネイル画像",
            "display_date" =>"掲載日付",
            "display_end_date" =>"掲載終了日付",
        ),
        "filters" =>array(
            array("filter" =>"sanitize"),
            array("target" =>"category",
                    "filter" =>"list_select",
                    "enum" =>"Post.category"),
            array("target" =>"display_date",
                    "filter" =>"date"),
            array("target" =>"display_end_date",
                    "filter" =>"date"),
        ),
        "ignore_empty_line" =>true,
    );

    /**
     * @page
     * @title 記事管理 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title 記事管理 一覧表示
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
     * @title 記事管理 入力フォーム
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
     * @title 記事管理 確認
     */
    public function act_entry_confirm ()
    {
        redirect("page:.entry_exec");
    }

    /**
     * @page
     * @title 記事管理 完了
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
     * @title 記事管理 削除
     */
    public function act_delete ()
    {
        if ($id = $this->request["id"]) {
            table("Post")->deleteById($id);
        }
        redirect("page:.view_list", array("back"=>"1"));
    }

    /**
     * @page
     * @title 記事管理 CSVダウンロード
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
            table("Post")->transactionBegin();
            while ($t=$csv->read_line()) {
                table("Post")->save($t);
            }
            table("Post")->transactionCommit();
            $this->forms["entry_csv"]->clear();
        }
        redirect("page:.view_list", array("back"=>"1"));
    }
}
