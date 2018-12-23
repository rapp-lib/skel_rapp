<?php
namespace R\App\Enum;

/**
 * @enum
 */
class UserEnum extends Enum_App
{
    protected static $values_pref = array(
        "1" => "北海道",
        "2" => "青森県",
    );
    protected static $values_deliv_flg = array(
        "1" => "ON",
        "2" => "OFF",
    );
    protected static $values_accept_flg = array(
        "1" => "承認待ち",
        "2" => "承認",
        "3" => "削除",
    );
    protected static $values_download_flg = array(
        "1" => "未",
        "2" => "済",
    );
    protected static $values_erase_flg = array(
        "1" => "未",
        "2" => "済",
    );
}
