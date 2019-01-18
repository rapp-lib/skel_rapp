<?php
namespace R\App\Controller;

/**
 * @controller
 */
class IndexController extends Controller_Guest
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
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("id")),
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
    public function act_index ()
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
                    return $this->redirect($this->forms["login"]["redirect"] ?: "id://user_products.list");
                } else {
                    $this->vars["login_error"] = true;
                }
            }
        }

        // 更新情報
        $this->vars["news_ts"] = $this->forms["news_search"]->search()->select();

        // 注意事項
        $this->vars["notice_ts"] = $this->forms["notice_search"]->search()->select();
    }
    /**
     * @page
     */
    public function act_index_static ()
    {
    }
}
