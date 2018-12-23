<?php
namespace R\App\Enum;

/**
 * @enum
 */
class UserProductEnum extends Enum_App
{
    protected static function values_user ($keys)
    {
        $query = table("User");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "company_name");
    }
    protected static function values_product ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
    protected static $values_accept_flg = array(
        "1" => "承認待ち",
        "2" => "承認",
        "3" => "削除",
    );
}
