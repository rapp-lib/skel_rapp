<?php
namespace R\App\Table;

/**
 * @table
 */
class CategoryTable extends Table_App
{
    protected static $table_name = "Category";
    protected static $cols = array(
        "category_type"=>array(
            "type"=>"integer",
            "comment"=>"種類",
        ),
        "parent_category_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"Category",
            "comment"=>"大分類ID",
        ),
        "name"=>array(
            "type"=>"text",
            "comment"=>"分類名",
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
        array("category_type", "enum", "enum"=>"Category.category_type"),
        array("parent_category_id", "enum", "enum"=>"Category.category"),
    );
    protected static $aliases = array(
        "category_type"=>array(
            "category_type_label"=>array("enum"=>"Category.category_type"),
        ),
        "parent_category_id"=>array(
            "parent_category_id_label"=>array("enum"=>"Category.category"),
            "category"=>array("type"=>"belongs_to", "table"=>"Category"),
        ),
        "id"=>array(
            "products"=>array("type"=>"has_many", "table"=>"Product"),
            "categories"=>array("type"=>"has_many", "table"=>"Category"),
        ),
    );
    protected static $def = array(
        "comment" => "分類",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
