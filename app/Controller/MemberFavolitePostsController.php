<?php

namespace R\App\Controller;

/**
 * @controller
 */
class MemberFavolitePostsController extends Controller_App
{
    /**
     * 認証設定
     */
    protected static $access_as = "member";
    protected static $priv_required = true;

    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "search_page" => ".view_list",
        "search_table" => "FavolitePost",
        "fields" => array(
            "freeword" => array("search"=>"word", "target_col"=>array("member_id",)),
            "p" => array("search"=>"page", "volume"=>20),
            "order" => array("search"=>"sort", "default"=>"FavolitePost.id@ASC"),
        ),
    );

    /**
     * @page
     * @title お気に入り変更 TOP
     */
    public function act_index ()
    {
        redirect("page:.view_list");
    }

    /**
     * @page
     * @title お気に入り変更 入力フォーム
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
     * @title お気に入り変更 確認
     */
    public function act_entry_confirm ()
    {
    }

    /**
     * @page
     * @title お気に入り変更 完了
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
    }

    /**
     * @page
     * @title お気に入り 一覧表示
     */
    public function act_view_list ()
    {
        if ($this->forms["search"]->receive()) {
            $this->forms["search"]->save();
        } elseif ($this->request["back"]) {
            $this->forms["search"]->restore();
        }

        // ログイン中の会員IDをフリーワードにセット
        $this->vars["ts"] = $this->forms["search"]->search()
            ->findBy("member_id", auth("member")->getId())
            ->join(table("Post"), array("Post.id=FavolitePost.post_id"))
            ->select();

        report($this->vars["ts"]);
    }

    /**
     * @page
     * @title お気に入り 追加・削除
     */
    public function act_add_favolite ()
    {
        // お気に入り登録に必要な情報を取得
        $post_id =$this->request["id"];
        $member_id =auth("member")->getId();

        // 記事が存在するか確認
        $post_t = table("Post")
            ->selectById($post_id);

        // 記事が存在する　＆＆　ログイン中である場合
        if ($post_t && $member_id) {

            // お気に入り記事が存在するか確認
            $t= table("FavolitePost")
                ->findBy(array("member_id" =>$member_id, "post_id" =>$post_id))
                ->selectOne();

            // お気に入り記事が存在する場合
            if ($t) {

                // お気に入り削除
                table("FavolitePost")->deleteById($t["id"]);

                $resurt ="del";
                $mes ="削除しました";
                $a_text ="お気に入り追加";

            // お気に入り記事が存在しない場合
            } else {

                // お気に入り追加
                table("FavolitePost")->insert(array(
                    "member_id" =>$member_id,
                    "post_id" =>$post_id,
                ));

                $resurt ="add";
                $mes ="追加しました";
                $a_text ="お気に入り削除";
            }

            // json登録
            $this->vars["resurt"] =$resurt;
            $this->vars["msg"] =$mes;
            $this->vars["a_text"] =$a_text;

        } else {
            redirect("page:index.index");
        }
    }

    /**
     * @page
     * @title 会員管理 削除
     */
    public function act_delete ()
    {
        if ($id = $this->request["id"]) {

            // お気に入り削除に必要な会員情報を取得
            $member_id =auth("member")->getId();

            // 存在するか確認
            $t= table("FavolitePost")
                ->findBy(array("id" =>$id, "member_id" =>$member_id))
                ->selectOne();

            report($t);
            // 直打ちURLにて悪意ある行動を阻止
            if (!$t) {
                redirect("page:.view_list", array("back"=>"1"));
            }

            // 削除
            table("FavolitePost")->deleteById($id);
        }
        redirect("page:.view_list", array("back"=>"1"));
    }
}
