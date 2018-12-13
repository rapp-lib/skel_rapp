<?php
namespace R\App\Enum;

/**
 * @enum
 */
class ProductEnum extends Enum_App
{
    protected static function values_product_category ($keys)
    {
        $query = table("ProductCategory");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
}
