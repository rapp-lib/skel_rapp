<?php
namespace R\App\Enum;

/**
 * @enum
 */
class ProductCategoryEnum extends Enum_App
{
    protected static $values_kind = array(
        "1" => "大分類",
        "2" => "中分類",
    );
    protected static $values_parent_id = array(
        "1" => "Value-1",
        "2" => "Value-2",
        "3" => "Value-3",
    );
}
