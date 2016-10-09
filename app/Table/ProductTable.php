<?php
namespace R\App\Table;

/**
 * @table
 */
class ProductTable extends Table_App
{
    /**
     * テーブル定義
     */
    protected static $table_name = "Product";
    protected static $cols = array(
        "name" => array("type"=>"text", "comment"=>"名称"),
        "img" => array("type"=>"text", "comment"=>"写真"),
        "category" => array("type"=>"text", "comment"=>"カテゴリ"),
        "open_date" => array("type"=>"datetime", "comment"=>"公開日時"),
        "owner_member_id" => array("type"=>"integer", "comment"=>"所有者会員ID"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "comment"=>"登録日時"),
        "del_flg" => array("type"=>"integer", "del_flg"=>true, "default"=>0, "comment"=>"削除フラグ"),
    );
    protected static $def = array(
        "indexes" => array(),
    );
}