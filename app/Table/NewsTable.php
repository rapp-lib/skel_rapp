<?php
namespace R\App\Table;

/**
 * @table
 */
class NewsTable extends Table_App
{
    protected static $table_name = "News";
    protected static $cols = array(
        "date"=>array(
            "type"=>"datetime",
            "comment"=>"日付",
        ),
        "contents"=>array(
            "type"=>"text",
            "comment"=>"内容",
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
    );
    protected static $def = array(
        "comment" => "更新情報",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
