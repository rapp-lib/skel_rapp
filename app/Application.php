<?php
namespace R\App;

use R\Lib\Core\Application_Base;

/**
 * アプリケーションの定義
 */
class Application extends Application_Base
{
    private static $instance = null;
    /**
     * インスタンスを取得
     */
    public static function getInstance ()
    {
        if ( ! isset(self::$instance)) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }
    /**
     * 初期化処理
     */
    public function init ()
    {
        parent::init();
        $this->addProvider(array(
            "router" => "R\\App\\Provider\\Router::getInstance",
            "route" => "R\\App\\Provider\\Router::getRouteInstance",
            "config" => "R\\Lib\\Core\\Configure::getInstance",
            "table" => "R\\Lib\\Table\\TableFactory::getInstance",
            "form" => "R\\Lib\\Form\\FormFactory::getInstance",
            "enum" => "R\\Lib\\Enum\\EnumFactory::getInstance",
            "enum_select" => "R\\Lib\\Enum\\EnumFactory::selectValue",
            "file_storage" => "R\\Lib\\FileStorage\\FileStorageManager::getInstance",
            "builder" => "R\\Lib\\Builder\\WebappBuilder::getInstance",
            "util" => "R\\Lib\\Core\\UtilProxyManager::getProxy",
            "extension" => "R\\Lib\\Core\\ExtentionManager::getExtention",
            "auth" => "R\\Lib\\Auth\\AccountManager::getInstance",
            "asset" => "R\\Lib\\Asset\\AssetManager::getInstance",
        ));
        if (php_sapi_name()==="cli") {
            $this->addProvider(array(
                "report" => "R\\App\\Provider\\ConsoleReport::getInstance",
                "request" => "R\\App\\Provider\\ConsoleRequest::getInstance",
                "response" => "R\\App\\Provider\\ConsoleResponse::getInstance",
            ));
        } else {
            $this->addProvider(array(
                "report" => "R\\App\\Provider\\HttpReport::getInstance",
                "request" => "R\\App\\Provider\\HttpRequest::getInstance",
                "response" => "R\\App\\Provider\\HttpResponse::getInstance",
                "session" => "R\\Lib\\Core\\Session::getInstance",
            ));
            // セッションの開始
            ini_set("session.gc_maxlifetime",86400);
            ini_set("session.cookie_lifetime",86400);
            ini_set("session.cookie_httponly",true);
            ini_set("session.cookie_secure",$_SERVER['HTTPS']);
            session_cache_limiter('');
            header("Pragma: public");
            header("Expires: Thu, 19 Nov 1981 08:52:00 GMT");
            header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
            header("P3P: CP='UNI CUR OUR'");
            session_start();
            ob_start();
            start_dync();
        }
    }
    /**
     * APP_ROOT_DIRの取得
     */
    public function getAppRootDir ()
    {
        return __DIR__."/..";
    }
    /**
     * TMP_DIRの取得
     */
    public function getTmpDir ()
    {
        return __DIR__."/../tmp";
    }
    /**
     * デバッグモードの取得
     */
    public function getDebugLevel ()
    {
        return $this->config("Config.debug_level");
    }
}
