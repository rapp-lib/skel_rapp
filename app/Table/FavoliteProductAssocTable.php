<?php
namespace R\App\Table;

/**
 * @table
 */
class FavoliteProductAssocTable extends Table_App
{
    protected static $cols = array(
        "customer_id" => array("type"=>"integer", "comment"=>"会員ID"),
        "product_id" => array("type"=>"integer", "comment"=>"製品ID"),
        "id" => array("type"=>"integer", "id"=>true, "autoincrement"=>true, "comment"=>"ID"),
    );
    protected static $fkeys = array(
    );
    protected static $def = array(
        "table_name" => "FavoliteProductAssoc",
        "indexes" => array(),
    );
}