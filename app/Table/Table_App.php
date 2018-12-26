<?php
namespace R\App\Table;

class Table_App
{
    /**
     * テーブルの定義
     */
    protected static $table_name = null;
    protected static $ds_name = "default";
    protected static $def = array();
    protected static $cols = array();
    protected static $aliases = array();
    protected static $fkey_routes = array();
    protected static $rules = array();
    /**
     * Table定義の取得
     */
    public static function getDef ()
    {
        $table_def = (array)static::$def;
        $table_def["table_name"] = static::$table_name;
        $table_def["ds_name"] = static::$ds_name;
        $table_def["cols"] = (array)static::$cols;
        $table_def["aliases"] = (array)static::$aliases;
        $table_def["fkey_routes"] = (array)static::$fkey_routes;
        $table_def["rules"] = (array)static::$rules;
        return $table_def;
    }
}
