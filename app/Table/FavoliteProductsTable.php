<?php
namespace R\App\Table;

/**
 * @table
 */
class FavoliteProductsTable extends Table_App
{
    protected static $cols = array(
        "member_id" => array("type"=>"integer", "comment"=>"会員ID"),
        "product_id" => array("type"=>"integer", "comment"=>"製品ID"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
    );
    protected static $refs = array(
    );
    protected static $def = array(
        "table_name" => "FavoliteProducts",
        "indexes" => array(),
    );
}