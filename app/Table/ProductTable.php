<?php
namespace R\App\Table;

/**
 * @table
 */
class ProductTable extends Table_App
{
    protected static $table_name = "Product";
    protected static $cols = array(
        "parent_category_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"ProductCategory",
            "comment"=>"大分類ID",
        ),
        "child_category_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"ProductCategory",
            "comment"=>"中分類ID",
        ),
        "name"=>array(
            "type"=>"text",
            "comment"=>"製品名",
        ),
        "model"=>array(
            "type"=>"text",
            "comment"=>"型名",
        ),
        "image"=>array(
            "type"=>"text",
            "comment"=>"画像",
        ),
        "manual"=>array(
            "type"=>"text",
            "comment"=>"取扱説明書",
        ),
        "release_date"=>array(
            "type"=>"datetime",
            "comment"=>"公開日",
        ),
        "description"=>array(
            "type"=>"text",
            "comment"=>"説明",
        ),
        "status"=>array(
            "type"=>"integer",
            "default"=>1,
            "comment"=>"ステータス",
        ),
        "relation_products"=>array(
            "assoc"=>array(
                "table"=>"RelationProduct",
            ),
            "comment"=>"関連ファイル",
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
        array("parent_category_id", "enum", "enum"=>"Product.product_category"),
        array("child_category_id", "enum", "enum"=>"Product.product_category"),
    );
    protected static $aliases = array(
        "parent_category_id"=>array(
            "parent_category_id_label"=>array("enum"=>"Product.product_category"),
            "product_category"=>array("type"=>"belongs_to", "table"=>"ProductCategory"),
        ),
        "child_category_id"=>array(
            "child_category_id_label"=>array("enum"=>"Product.product_category"),
            "product_category"=>array("type"=>"belongs_to", "table"=>"ProductCategory"),
        ),
        "id"=>array(
            "user_products"=>array("type"=>"has_many", "table"=>"UserProduct"),
            "relation_products"=>array("type"=>"has_many", "table"=>"RelationProduct"),
        ),
    );
    protected static $def = array(
        "comment" => "製品",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_userStatus ()
    {
        if (app()->user->getCurrentPriv("user") && $col_name = $this->getColNameByAttr("status")) {
            $this->query->where($this->getAppTableName().".".$col_name,1);
         }
     }
}
