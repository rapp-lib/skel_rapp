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
        "nickname" => array("type"=>"text", "comment"=>"ニックネーム"),
        "mail" => array("type"=>"text", "mail"=>true, "comment"=>"メールアドレス"),
        "gender" => array("type"=>"text", "comment"=>"性別"),
        "birthday" => array("type"=>"datetime", "comment"=>"生年月日"),
        "job" => array("type"=>"text", "format"=>"json", "comment"=>"職業"),
        "interest" => array("type"=>"text", "format"=>"json", "comment"=>"興味関心"),
        "login_id" => array("type"=>"text", "login_id"=>true, "comment"=>"ログインID"),
        "login_pw" => array("type"=>"text", "login_pw"=>true, "hash_pw"=>true,"comment"=>"パスワード"),
        "mail_cred" => array("type"=>"text", "mail_cred"=>true, "comment"=>"メールキー"),
        "mail_cred_date" => array("type"=>"text", "mail_cred_date"=>true, "comment"=>"メールキー発行日時"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "reg_date"=>true, "comment"=>"登録日時"),
        "del_flg" => array("type"=>"integer", "del_flg"=>true, "default"=>0, "comment"=>"削除フラグ"),
    );
    protected static $def = array(
        "indexes" => array(),
    );
}