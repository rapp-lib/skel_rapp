<?php
namespace R\App\Table;

/**
 * @table
 */
class ProductCategoryTable extends Table_App
{
    protected static $table_name = "ProductCategory";
    protected static $cols = array(
        "kind"=>array(
            "type"=>"integer",
            "comment"=>"種類",
        ),
        "parent_id"=>array(
            "type"=>"integer",
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
        array("kind", "enum", "enum"=>"ProductCategory.kind"),
        array("parent_id", "enum", "enum"=>"ProductCategory.parent_id"),
    );
    protected static $aliases = array(
        "kind"=>array(
            "kind_label"=>array("enum"=>"ProductCategory.kind"),
        ),
        "parent_id"=>array(
            "parent_id_label"=>array("enum"=>"ProductCategory.parent_id"),
        ),
        "id"=>array(
            "products"=>array("type"=>"has_many", "table"=>"Product"),
        ),
    );
    protected static $def = array(
        "comment" => "分類",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
