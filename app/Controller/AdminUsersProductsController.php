<?php
namespace R\App\Controller;

/**
 * @controller
 */
class AdminUsersProductsController extends Controller_Admin
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "admin_users_products.list",
        "search_table" => "UserProduct",
        "search_joins" => array(
            array("User", array("User.id=UserProduct.user_id")),
        ),
        "fields" => array(
            "sort" => array("search"=>"sort", "cols"=>array("UserProduct.id", "model", "csv_sort"=>"User.reg_date ASC, UserProduct.reg_date ASC")),
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
        $this->vars["ts"] = $this->forms["search"]->search()->findBy("UserProduct.accept_flg", "1")->select();
        $this->vars["complete_flg"] = $this->input["complete_flg"];
    }
    /**
     * 入力フォーム
     */
    protected static $form_entry = array(
        "form_page" => "admin_users_products.form",
        "csrf_check" => true,
        "table" => "UserProduct",
        "fields" => array(
            "id"=>array("label"=>"ID"),
            "product_id"=>array("label"=>"型名（製品名）"),
            "serial_number"=>array("label"=>"シリアルNo."),
            "purchase_source"=>array("label"=>"購入元"),
            "purchase_reason"=>array("label"=>"購入理由"),
            "accept_flg"=>array("label"=>"承認ステータス"),
            "mail" =>array("label"=>"追加希望ユーザー", "col_values_clause"=>false),
        ),
        "rules" => array(
            "product_id",
            "serial_number",
            array("serial_number", "length", "max"=>10),
            array("purchase_source", "length", "max"=>100),
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
                $this->forms["entry"]["mail"] = $t["user"]["mail"];
            }
            if ( ! $this->forms["entry"]["user_id"]) {
                $this->forms["entry"]["user_id"] = $this->input["user_id"];
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
            if ($this->forms["entry"]["accept_flg"] == "3") {
                // 削除処理
                table("UserProduct")->deleteById($this->forms["entry"]["id"]);
                $complete_flg = "delete";
            } else {
                // 更新処理
                $t = $this->forms["entry"]->getTableWithValues()->save()->getSavedRecord();
                if ($this->forms["entry"]["accept_flg"] == "2") {
                    // ユーザ向け通知メールの送信
                    app("mailer")->send("mail://admin_users_products.reply.html", array("t"=>$t), function($message){});
                }
                $complete_flg = "update";
            }
            $this->forms["entry"]->clear();
        }
        return $this->redirect("id://.list", array("back"=>"1","complete_flg"=>$complete_flg));
    }
    /**
     * @page
     */
    public function act_delete ()
    {
        if ($id = $this->input["id"]) {
            table("UserProduct")->deleteById($id);
        }
        return $this->redirect("id://admin_users_products.list", array("back"=>"1"));
    }
    /**
     * CSV設定
     */
    protected static $form_csv = array(
        "table" => "UserProduct",
        "fields" => array(
            "id"=>array("label"=>"#ID"),
            "user.mail"=>array("label"=>"メールアドレス"),
            "product.model"=>array("label"=>"型名"),
            "serial_number"=>array("label"=>"シリアルNo"),
            "purchase_source"=>array("label"=>"購入元"),
            "purchase_reason"=>array("label"=>"購入理由"),
            "reg_date"=>array("label"=>"登録日付"),
            "user.id"=>array("label"=>"ユーザID"),
            "user.reg_date"=>array("label"=>"ユーザ登録日付"),
        ),
        "rules" => array(
            "serial_number",
        ),
        "csv_setting" => array(
            "ignore_empty_line" => true,
            "rows" => array(),
            "filters" => array(
                array("product_id", "enum_value", "enum"=>"UserProduct.product"),
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
        // CSVダウンロード時は固定のソートを用いる
        $this->forms["search"]["sort"] = "csv_sort";
        $ts = $this->forms["search"]->search()->findBy(array("UserProduct.accept_flg" => "2", "User.accept_flg" => "2"))->removePagenation()->select();
        // CSVファイルの書き込み
        $csv = $this->forms["csv"]->openCsvFile("php://temp", "w");
        foreach ($ts as $t) $csv->writeRecord($t);
        // データ出力
        return app()->http->response("stream", $csv->getHandle(), array("headers"=>array(
            'content-type' => 'application/octet-stream',
            'content-disposition' => 'attachment; filename='.'UserProduct.csv'
        )));
    }
}
