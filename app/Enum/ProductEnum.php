<?php
namespace R\App\Enum;

/**
 * @enum
 */
class ProductEnum extends Enum_App
{
    protected static function values_category ($keys)
    {
        $query = table("Category");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
    protected static $values_display_status = array(
        "1" => "公開",
        "2" => "下書き",
    );
}
