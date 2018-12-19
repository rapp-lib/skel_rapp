<?php
namespace R\App\Enum;

/**
 * @enum
 */
class RelationProductEnum extends Enum_App
{
    protected static $values_class = array(
        "1" => "ソフトウェア",
        "2" => "技術情報",
        "3" => "分析情報",
    );
    protected static function values_common_information ($keys)
    {
        $query = table("CommonInformation");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "title");
    }
    protected static $values_status = array(
        "1" => "公開",
        "2" => "下書き",
    );
}
