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
    protected static function values_product_files ($keys)
    {
        $query = table("ProductFile");
        if ($keys) $query->findById($keys);
        return $query->select()->getHashedBy("id", "name");
    }
    protected static function values_parent_category_id ($keys)
    {
        $query = table("Category");
        if ($keys) $query->findById($keys);
        return $query->findBy("parent_category_id IS NULL")->select()->getHashedBy("id", "name");
    }
    protected static function values_child_category_id ($keys)
    {
        if (count($keys)!==1) return array();
        $query = table("Category");
        return $query->findBy("parent_category_id",$keys)->select()->getHashedBy("parent_category_id", "id", "name");
    }
}
