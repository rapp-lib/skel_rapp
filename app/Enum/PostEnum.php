<?php
namespace R\App\Enum;

/**
 * @enum
 */
class PostEnum extends Enum_App
{
    /**
     * @enumset category
     */
    protected static $values_category = array(
        "1" =>"WORKOUT",
        "2" =>"FASHION",
        "3" =>"FOOD",
        "4" =>"PEOPLE",
        "5" =>"EVENT",
        "6" =>"FACILITY",
        "7" =>"WORLD",
        "8" =>"COLMUN",
    );
}
