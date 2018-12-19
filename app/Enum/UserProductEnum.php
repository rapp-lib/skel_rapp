<?php
namespace R\App\Enum;

/**
 * @enum
 */
class UserProductEnum extends Enum_App
{
    protected static function values_product ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
    protected static $values_accept_flg = array(
        "1" => "承認",
        "2" => "承認待ち",
        "3" => "削除",
    );
}
