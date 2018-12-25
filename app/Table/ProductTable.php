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
            "fkey_for"=>"Category",
            "comment"=>"大分類ID",
        ),
        "child_category_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"Category",
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
            "release_date"=>true,
            "comment"=>"公開日",
        ),
        "description"=>array(
            "type"=>"text",
            "comment"=>"説明",
        ),
        "display_status"=>array(
            "type"=>"integer",
            "default"=>1,
            "display_status"=>true,
            "comment"=>"公開ステータス",
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
        array("parent_category_id", "enum", "enum"=>"Product.category"),
        array("child_category_id", "enum", "enum"=>"Product.category"),
        array("display_status", "enum", "enum"=>"Product.display_status"),
    );
    protected static $aliases = array(
        "parent_category_id"=>array(
            "parent_category_id_label"=>array("enum"=>"Product.category"),
            "parent_category"=>array("type"=>"belongs_to", "table"=>"Category"),
        ),
        "child_category_id"=>array(
            "child_category_id_label"=>array("enum"=>"Product.category"),
            "child_category"=>array("type"=>"belongs_to", "table"=>"Category"),
        ),
        "display_status"=>array(
            "display_status_label"=>array("enum"=>"Product.display_status"),
        ),
        "id"=>array(
            "user_products"=>array("type"=>"has_many", "table"=>"UserProduct"),
            "product_files"=>array("type"=>"has_many", "table"=>"ProductFile"),
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
        if (! app()->user->getCurrentPriv("admin") && $col_name = $this->getColNameByAttr("status")) {
            $this->query->where($this->getAppTableName().".".$col_name,1);
        } else {
            return false;
        }
    }

    /**
     * @hook on_read
     * ユーザ表示項目を関連付ける
     */
    protected function on_read_userReleaseDate ()
    {
        if (! app()->user->getCurrentPriv("admin") && $col_name = $this->getColNameByAttr("release_date")) {
            $this->query->where($this->getAppTableName().".".$col_name." <= CURRENT_DATE");
        } else {
            return false;
        }
    }
}
