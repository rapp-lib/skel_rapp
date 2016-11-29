<?php
namespace R\App\Enum;

/**
 * @enum
 */
class MemberEnum extends Enum_App
{
    /**
     * @enumset gender
     */
    protected static $values_gender = array(
        "1" =>"男性",
        "2" =>"女性",
    );
    /**
     * @enumset job
     */
    protected static $values_job = array(
        "1" =>"会社員",
        "2" =>"会社経営",
        "3" =>"自由業",
        "4" =>"パートアルバイト",
        "5" =>"学生",
        "6" =>"専業主婦",
        "7" =>"その他",
    );
    /**
     * @enumset interest
     */
    protected static $values_interest = array(
        "1" =>"筋力トレーニング",
        "2" =>"ヨガ",
        "3" =>"有酸素運動",
        "4" =>"食事管理",
        "5" =>"ファッション",
        "6" =>"フィットネス関連施設",
        "7" =>"フィットネスイベント",
        "8" =>"海外情報",
    );
}
