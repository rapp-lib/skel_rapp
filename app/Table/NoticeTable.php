<?php
namespace R\App\Table;

/**
 * @table
 */
class NoticeTable extends Table_App
{
    protected static $table_name = "Notice";
    protected static $cols = array(
        "number"=>array(
            "type"=>"integer",
            "comment"=>"項番",
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
        "comment" => "ご注意事項",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
