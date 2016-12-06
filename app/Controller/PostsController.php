<?php

namespace R\App\Controller;

/**
 * @controller
 */
class PostsController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = false;

    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "search_page" => ".view_list",
        "search_table" => "Post",
        "fields" => array(
            "freeword" => array("search"=>"word", "target_col"=>array("title","content","youtube_id",)),
            "category" => array("search"=>"where", "target_col"=>"category"),
            "p" => array("search"=>"page", "volume"=>20),
            "order" => array("search"=>"sort", "default"=>"id@ASC"),
        ),
    );

    /**
     * @page
     * @title 記事表示 TOP
     */
    public function act_index ()
    {
        // 閲覧数順に記事を取得
        $pv_ts= table("Post")
            ->findBy(array("pv_count >" =>0, "draft_flg" =>1, "NOW() BETWEEN display_date AND display_end_date"))
            ->orderBy("pv_count", false)
            ->pagenate(false, "5")
            ->select();

        $cou =1;
        // 同率順位などがあるため、成形
        foreach($pv_ts as $k =>$v) {

            // 同率
            if ($v["pv_count"] == $last_pv) {

                $rank_ts[$cou]=array("rank"=> $rank_ts[$cou - 1]["rank"], "Post" =>$v);

            // そのまま格納
            } else {

                $rank_ts[$cou]=array("rank"=> $cou, "Post" =>$v);
            }

            $last_pv =$v["pv_count"];
            $cou ++;
        }

        $this->vars["rank_ts"] =$rank_ts;

        report($rank_ts);

        // 最新記事の取得
        $this->vars["new_ts"] = table("Post")
            ->findBy(array("draft_flg" =>1, "NOW() BETWEEN display_date AND display_end_date"))
            ->orderBy("display_date", false)
            ->pagenate(false, "5")
            ->select();

        report($this->vars["new_ts"]);



        // カテゴリーごとに記事を取得
        foreach (enum("Post.category") as $k =>$v) {
            $ts[$v] = table("Post")
                ->findBy(array("category" =>$k, "draft_flg" =>1, "NOW() BETWEEN display_date AND display_end_date"))
                ->orderBy("display_date", false)
                ->pagenate(false, "5")
                ->select();

            $cates[$k] =$v;
        }

        $this->vars["cates"] =$cates;
        $this->vars["ts"] =$ts;

        report($this->vars["ts"]);
        report($this->vars["cates"]);
    }

    /**
     * @page
     * @title 記事表示 一覧表示
     */
    public function act_view_list ()
    {
        if ($this->forms["search"]->receive()) {
            $this->forms["search"]->save();
        } elseif ($this->request["back"]) {
            $this->forms["search"]->restore();
        }
        //report($this->request["cate_id"]);
        $this->vars["ts"] = $this->forms["search"]->search()->select();
        report($this->vars["ts"]);
    }

    /**
     * @page
     * @title 記事表示 一件表示
     */
    public function act_view_detail ()
    {
        // テスト
        //$this->request["id"] =21;
        // IDが存在する場合
        if ($id =$this->request["id"]) {

            // IDを条件に一件取得
            $this->vars["t"] = table("Post")
                ->findBy(array("id"=>$id, "draft_flg" =>1))
                ->selectOne();

            report($this->vars["t"]);

            // 取得出来ない場合はエラー
            if (!$this->vars["t"]) {
                redirect("page:.index");
            }

            // 閲覧数の更新（テスト）
            table("Post")->updateById($id, array(
                    "pv_count" =>$this->vars["t"]["pv_count"] + 1
            ));

            // 会員ログインしている場合、お気に入りボタンを作成
            if ($member_id =auth("member")->getId()) {

                $fabo_t= table("FavolitePost")
                    ->findBy(array("member_id" =>$member_id, "post_id" =>$this->vars["t"]["id"]))
                    ->selectOne();

                if ($fabo_t) {
                    $a_text ="お気に入り削除";
                } else {
                    $a_text ="お気に入り追加";
                }

                $this->vars["a_text"] =$a_text;
            }

        // IDが存在しない場合
        } else {

            redirect("page:.index");
        }
    }
    /**
     * @page
     * @title 記事表示 ランキング一覧表示
     */
    public function act_rank_view_list ()
    {
        // 閲覧数順に記事を取得
        $ts= table("Post")
            ->findBy(array("pv_count >" =>0, "draft_flg" =>1, "NOW() BETWEEN display_date AND display_end_date"))
            ->orderBy("pv_count", false)
            ->pagenate(false, "10")
            ->select();

        $cou =1;
        // 同率順位などがあるため、成形
        foreach($ts as $k =>$v) {

            // 同率
            if ($v["pv_count"] == $last_pv) {

                $rank_ts[$cou]=array("rank"=> $rank_ts[$cou - 1]["rank"], "Post" =>$v);

            // そのまま格納
            } else {

                $rank_ts[$cou]=array("rank"=> $cou, "Post" =>$v);
            }

            $last_pv =$v["pv_count"];
            $cou ++;
        }
        $this->vars["ts"] =$rank_ts;

        report($ts);
        report($rank_ts);
    }
}
