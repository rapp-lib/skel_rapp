<?php
namespace R\App\Table;

/**
 * @table
 */
class PostTable extends Table_App
{
    /**
     * テーブル定義
     */
    protected static $table_name = "Post";
    protected static $cols = array(
        "title" => array("type"=>"text", "comment"=>"タイトル"),
        "content" => array("type"=>"text", "comment"=>"本文"),
        "youtube_id" => array("type"=>"text", "comment"=>"youtubeID"),
        "discovery_rank" => array("type"=>"integer", "comment"=>"DISCOVERY枠順位"),
        "keyword" => array("type"=>"text", "comment"=>"META KEYWORD"),
        "description" => array("type"=>"text", "comment"=>"META DESCRIPTION"),
        "category" => array("type"=>"text", "comment"=>"カテゴリー"),
        "thumbnail_img" => array("type"=>"text", "comment"=>"サムネイル画像"),
        "display_date" => array("type"=>"datetime", "comment"=>"掲載日付"),
        "display_end_date" => array("type"=>"datetime", "comment"=>"掲載終了日付"),
        "draft_flg" => array("type"=>"integer", "comment"=>"下書きフラグ"),
        "pv_count" => array("type"=>"integer", "comment"=>"閲覧数"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
        "reg_date" => array("type"=>"datetime", "reg_date"=>true, "comment"=>"登録日時"),
        "del_flg" => array("type"=>"integer", "del_flg"=>true, "default"=>0, "comment"=>"削除フラグ"),
    );
    protected static $def = array(
        "indexes" => array(),
    );
}