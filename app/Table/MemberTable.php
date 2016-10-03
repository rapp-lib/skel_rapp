<?php
namespace R\App\Table;

/**
 * @table
 */
class MemberTable extends Table_App
{
    /**
     * テーブル定義
     */
    protected static $table_name = "Member";
    protected static $cols = array(
        "name" => array("type"=>"text", "comment"=>"氏名"),
        "mail" => array("type"=>"text", "comment"=>"メールアドレス"),
        "login_pw" => array("type"=>"text", "comment"=>"パスワード"),
        "favorite_producs" => array("comment"=>"お気に入り製品"),
        "imgs" => array("type"=>"text", "comment"=>"画像"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "comment"=>"登録日時"),
        "del_flg" => array("type"=>"integer", "del_flg"=>true, "default"=>0, "comment"=>"削除フラグ"),
    );
    protected static $refs = array(
    );
    protected static $def = array(
        "indexes" => array(),
    );
    protected static $ds_name = null;
}