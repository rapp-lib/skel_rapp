<?php
namespace R\App\Table;

/**
 * @table
 */
class CommonFileTable extends Table_App
{
    protected static $table_name = "CommonFile";
    protected static $cols = array(
        "file"=>array(
            "type"=>"text",
            "comment"=>"ファイル",
        ),
        "title"=>array(
            "type"=>"text",
            "comment"=>"タイトル",
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
    );
    protected static $aliases = array(
        "id"=>array(
            "product_files"=>array("type"=>"has_many", "table"=>"ProductFile"),
        ),
    );
    protected static $def = array(
        "comment" => "共通関連情報",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
