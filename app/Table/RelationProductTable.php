<?php
namespace R\App\Table;

/**
 * @table
 */
class RelationProductTable extends Table_App
{
    protected static $table_name = "RelationProduct";
    protected static $cols = array(
        "product_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"Product",
            "comment"=>"製品ID",
        ),
        "class"=>array(
            "type"=>"integer",
            "comment"=>"ファイル種別",
        ),
        "common_information_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"CommonInformation",
            "comment"=>"ファイル属性",
        ),
        "file"=>array(
            "type"=>"text",
            "comment"=>"ファイル",
        ),
        "link"=>array(
            "type"=>"text",
            "comment"=>"リンク",
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
        array("class", "enum", "enum"=>"RelationProduct.class"),
        array("common_information_id", "enum", "enum"=>"RelationProduct.common_information"),
    );
    protected static $aliases = array(
        "product_id"=>array(
            "product"=>array("type"=>"belongs_to", "table"=>"Product"),
        ),
        "class"=>array(
            "class_label"=>array("enum"=>"RelationProduct.class"),
        ),
        "common_information_id"=>array(
            "common_information_id_label"=>array("enum"=>"RelationProduct.common_information"),
            "common_information"=>array("type"=>"belongs_to", "table"=>"CommonInformation"),
        ),
    );
    protected static $def = array(
        "comment" => "関連ファイル",
        "indexes" => array(
            array("name"=>"product", "cols"=>array("product_id")),
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
