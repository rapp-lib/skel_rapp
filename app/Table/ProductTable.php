<?php
namespace R\App\Table;

/**
 * @table
 */
class ProductTable extends Table_App
{
    protected $cols = array(
        "name" => array("type"=>"text", "comment"=>"名称"),
        "img" => array("type"=>"text", "comment"=>"写真"),
        "category" => array("type"=>"text", "comment"=>"カテゴリ"),
        "open_date" => array("type"=>"datetime", "comment"=>"公開日時"),
        "id" => array("type"=>"integer", "key"=>"primary", "id"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "comment"=>"登録日時"),
        "update_date" => array("type"=>"timestamp", "comment"=>"最終更新日時"),
        "del_flg" => array("type"=>"integer", "default"=>0, "del_flg"=>true, "comment"=>"削除フラグ"),
    );
    protected $fkeys = array(
    );
}