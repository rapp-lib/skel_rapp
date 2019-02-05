<?php
namespace R\App\Controller;

/**
 * @controller
 */
class UserProductsFilesController extends Controller_User
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "user_products_files.list",
        "search_table" => "ProductFile",
        "fields" => array(
            "p" => array("search"=>"page", "volume"=>20),
            "sort" => array("search"=>"sort", "cols"=>array("id")),
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
        $this->vars["product_t"] = table("Product")->findMine()->selectById($this->forms["search"]["product_id"]);
        if ( ! $this->forms["search"]["product_id"]) return $this->response("badrequest");
        $this->vars["ts"] = table("ProductFile")->join("CommonFile", array("ProductFile.common_file_id = CommonFile.id"))->findBy("ProductFile.product_id", $this->vars["product_t"]["id"])->select();
        report($this->vars["t"]);
    }
}
