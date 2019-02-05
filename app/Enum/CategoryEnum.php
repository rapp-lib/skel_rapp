<?php
namespace R\App\Enum;

/**
 * @enum
 */
class CategoryEnum extends Enum_App
{
    protected static $values_category_type = array(
        "1" => "大分類",
        "2" => "中分類",
    );
    protected static function values_category ($keys)
    {
        $query = table("Category");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
    protected static function values_parent_category_id ($keys)
    {
        $query = table("Category");
        if ($keys) $query->findById($keys);
        return $query->findBy("parent_category_id IS NULL")->select()->getHashedBy("id", "name");
    }
}
