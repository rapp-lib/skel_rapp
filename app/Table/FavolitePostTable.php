<?php
namespace R\App\Table;

/**
 * @table
 */
class FavolitePostTable extends Table_App
{
    /**
     * テーブル定義
     */
    protected static $table_name = "FavolitePost";
    protected static $cols = array(
        "member_id" => array("type"=>"integer", "comment"=>"会員ID"),
        "post_id" => array("type"=>"integer", "comment"=>"記事ID"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
    );
    protected static $def = array(
        "indexes" => array(),
    );
}