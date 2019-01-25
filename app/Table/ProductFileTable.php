<?php
namespace R\App\Table;

/**
 * @table
 */
class ProductFileTable extends Table_App
{
    protected static $table_name = "ProductFile";
    protected static $cols = array(
        "product_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"Product",
            "comment"=>"製品ID",
        ),
        "file_type"=>array(
            "type"=>"integer",
            "comment"=>"ファイル種別",
        ),
        "common_file_id"=>array(
            "type"=>"integer",
            "fkey_for"=>"CommonFile",
            "comment"=>"ファイル属性",
        ),
        "file"=>array(
            "type"=>"text",
            "comment"=>"ファイル",
        ),
        "link"=>array(
            "type"=>"text",
            "comment"=>"リンクURL",
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
            "comment"=>"公開状態",
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
        array("file_type", "enum", "enum"=>"ProductFile.file_type"),
        array("common_file_id", "enum", "enum"=>"ProductFile.common_file"),
        array("display_status", "enum", "enum"=>"ProductFile.display_status"),
    );
    protected static $aliases = array(
        "product_id"=>array(
            "product"=>array("type"=>"belongs_to", "table"=>"Product"),
        ),
        "file_type"=>array(
            "file_type_label"=>array("enum"=>"ProductFile.file_type"),
        ),
        "common_file_id"=>array(
            "common_file_id_label"=>array("enum"=>"ProductFile.common_file"),
            "common_file"=>array("type"=>"belongs_to", "table"=>"CommonFile"),
        ),
        "display_status"=>array(
            "display_status_label"=>array("enum"=>"ProductFile.display_status"),
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
