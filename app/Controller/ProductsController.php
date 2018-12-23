<?php
namespace R\App\Controller;

/**
 * @controller
 */
class ProductsController extends Controller_Guest
{
    /**
     * 検索フォーム
     */
    protected static $form_search = array(
        "receive_all" => true,
        "search_page" => "products.list",
        "search_table" => "Product",
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
        $this->vars["ts"] = $this->forms["search"]->search()->select();
    }
}
