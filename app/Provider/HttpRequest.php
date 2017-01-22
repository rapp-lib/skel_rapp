<?php
namespace R\App\Provider;

class HttpRequest extends \R\Lib\Core\Request
{
    private static $instance = null;
    /**
     * インスタンスを取得
     */
    public static function getInstance ()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function __construct ()
    {
        parent::__construct();
        // Requestの取得
        $request_values = util("Func")->mapRecursive(function($value) {
            return htmlspecialchars($value, ENT_QUOTES);
        }, array_merge($_GET, $_POST));
        $this->exchangeArray($request_values);
    }
}
