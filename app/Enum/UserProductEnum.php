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
    protected static function values_product_bracket_name ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        $t = $query->select()->getHashedBy("id", "bracket_name");
        return $t;
    }
    protected static function values_product_image ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        $t = $query->select()->getHashedBy("bracket_name", "image");
        return $t;
    }
    protected static function values_product_id_image ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        $t = $query->select()->getHashedBy("id", "image");
        return $t;
    }
    protected static function values_product_name_id ($keys)
    {
        $query = table("Product");
        if ($keys) $query->findById($keys);
        $t = $query->select()->getHashedBy("bracket_name", "id");
        return $t;
    }
}
