<?php
namespace R\App\Table;

/**
 * @table
 */
class UserProductTable extends Table_App
{
    protected static $table_name = "UserProduct";
    protected static $cols = array(
        "user_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"User",
            "comment"=>"ユーザID",
        ),
        "product_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"Product",
            "comment"=>"製品ID",
        ),
        "model"=>array(
            "comment"=>"型名（製造名）",
        ),
        "serial_number"=>array(
            "type"=>"text",
            "serial_number"=>true,
            "comment"=>"シリアルNo",
        ),
        "purchase_source"=>array(
            "type"=>"text",
            "comment"=>"購入元",
        ),
        "purchase_reason"=>array(
            "type"=>"text",
            "comment"=>"購入理由",
        ),
        "accept_flg"=>array(
            "type"=>"integer",
            "default"=>2,
            "accept_flg"=>true,
            "comment"=>"承認フラグ",
        ),
        "accepted_date"=>array(
            "type"=>"datetime",
            "comment"=>"承認通過日付",
        ),
        "id"=>array(
            "type"=>"integer",
            "id"=>true,
            "autoincrement"=>true,
            "comment"=>"ID",
        ),
        "reg_date"=>array(
            "type"=>"datetime",
            "reg_date"=>true,
            "comment"=>"登録日時",
        ),
        "del_flg"=>array(
            "type"=>"integer",
            "default"=>0,
            "del_flg"=>true,
            "comment"=>"削除フラグ",
        ),
    );
    protected static $rules = array(
        array("product_id", "enum", "enum"=>"UserProduct.product"),
        array(
            "serial_number",
            "duplicate",
            "table"=>"UserProduct",
            "col_name"=>"serial_number",
            "id_field"=>"id",
        ),
        array("accept_flg", "enum", "enum"=>"UserProduct.accept_flg"),
    );
    protected static $aliases = array(
        "user_id"=>array(
            "user"=>array("type"=>"belongs_to", "table"=>"User"),
        ),
        "product_id"=>array(
            "product_id_label"=>array("enum"=>"UserProduct.product"),
            "product"=>array("type"=>"belongs_to", "table"=>"Product"),
        ),
        "accept_flg"=>array(
            "accept_flg_label"=>array("enum"=>"UserProduct.accept_flg"),
        ),
    );
    protected static $def = array(
        "comment" => "ユーザー製品",
        "indexes" => array(
            array("name"=>"user", "cols"=>array("user_id")),
            array("name"=>"product", "cols"=>array("product_id")),
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );

    /**
     * @hook chain
     * 未承認のみを対象とする
     */
    /*public function chain_findByAcceptFlg ()
    {
        $this->query->where($this->getQueryTableName().".".$this->getColNameByAttr("accept_flg"), "2");
    }*/
}
