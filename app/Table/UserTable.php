<?php
namespace R\App\Table;

/**
 * @table
 */
class UserTable extends Table_App
{
    protected static $table_name = "User";
    protected static $cols = array(
        "company_name"=>array(
            "type"=>"text",
            "comment"=>"会社名",
        ),
        "department"=>array(
            "type"=>"text",
            "comment"=>"部署",
        ),
        "position"=>array(
            "type"=>"text",
            "comment"=>"役職",
        ),
        "last_name"=>array(
            "type"=>"text",
            "comment"=>"氏名(姓)",
        ),
        "first_name"=>array(
            "type"=>"text",
            "comment"=>"氏名(名)",
        ),
        "last_name_kana"=>array(
            "type"=>"text",
            "comment"=>"氏名(セイ)",
        ),
        "first_name_kana"=>array(
            "type"=>"text",
            "comment"=>"氏名(メイ)",
        ),
        "mail"=>array(
            "type"=>"text",
            "login_id"=>true,
            "mail"=>true,
            "comment"=>"メール",
        ),
        "zip"=>array(
            "type"=>"text",
            "comment"=>"郵便番号",
        ),
        "pref"=>array(
            "type"=>"text",
            "comment"=>"都道府県",
        ),
        "city"=>array(
            "type"=>"text",
            "comment"=>"市区郡町村",
        ),
        "address"=>array(
            "type"=>"text",
            "comment"=>"番地",
        ),
        "buildings"=>array(
            "type"=>"text",
            "comment"=>"建物名",
        ),
        "tel"=>array(
            "type"=>"text",
            "comment"=>"電話番号",
        ),
        "fax"=>array(
            "type"=>"text",
            "comment"=>"FAX番号",
        ),
        "login_pw"=>array(
            "type"=>"text",
            "login_pw"=>true,
            "hash_pw"=>true,
            "comment"=>"パスワード",
        ),
        "products"=>array(
            "assoc"=>array(
                "table"=>"UserProduct",
            ),
            "comment"=>"ユーザー製品",
        ),
        "memo"=>array(
            "type"=>"text",
            "comment"=>"備考",
        ),
        "deliv_flg"=>array(
            "type"=>"integer",
            "comment"=>"配信",
        ),
        "admin_memo"=>array(
            "type"=>"text",
            "comment"=>"管理者備考",
        ),
        "accept_flg"=>array(
            "type"=>"integer",
            "default"=>1,
            "accept_flg"=>true,
            "comment"=>"承認フラグ",
        ),
        "accept_date"=>array(
            "type"=>"datetime",
            "accept_date"=>true,
            "comment"=>"承認通過日付",
        ),
        "download_flg"=>array(
            "type"=>"integer",
            "default"=>1,
            "download_flg"=>true,
            "comment"=>"DL済みフラグ",
        ),
        "download_date"=>array(
            "type"=>"datetime",
            "download_date"=>true,
            "comment"=>"DL済み日付",
        ),
        "erase_flg"=>array(
            "type"=>"integer",
            "default"=>1,
            "erase_flg"=>true,
            "comment"=>"抹消済みフラグ",
        ),
        "erase_date"=>array(
            "type"=>"datetime",
            "erase_date"=>true,
            "comment"=>"抹消済みフラグ日付",
        ),
        "last_login_date"=>array(
            "type"=>"datetime",
            "last_login_date"=>true,
            "comment"=>"最終ログイン日時",
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
        array("mail", "format", "format"=>"mail"),
        array(
            "mail",
            "duplicate",
            "table"=>"User",
            "col_name"=>"mail",
            "id_field"=>"id",
            "id_role" =>"user",
        ),
        array("zip", "format", "format"=>"zip"),
        array("pref", "enum", "enum"=>"User.pref"),
        array("tel", "length", "max"=>20),
        array("fax", "length", "max"=>20),
        array("deliv_flg", "enum", "enum"=>"User.deliv_flg"),
        array("accept_flg", "enum", "enum"=>"User.accept_flg"),
        array("download_flg", "enum", "enum"=>"User.download_flg"),
        array("erase_flg", "enum", "enum"=>"User.erase_flg"),
    );
    protected static $aliases = array(
        "pref"=>array(
            "pref_label"=>array("enum"=>"User.pref"),
        ),
        "deliv_flg"=>array(
            "deliv_flg_label"=>array("enum"=>"User.deliv_flg"),
        ),
        "accept_flg"=>array(
            "accept_flg_label"=>array("enum"=>"User.accept_flg"),
        ),
        "download_flg"=>array(
            "download_flg_label"=>array("enum"=>"User.download_flg"),
        ),
        "erase_flg"=>array(
            "erase_flg_label"=>array("enum"=>"User.erase_flg"),
        ),
        "id"=>array(
            "user_products"=>array("type"=>"has_many", "table"=>"UserProduct"),
        ),
    );
    protected static $def = array(
        "comment" => "ユーザー",
        "indexes" => array(
            array("name"=>"visible", "cols"=>array("del_flg")),
        ),
    );
}
