<?php
namespace R\App;

use R\Lib\Core\Application as Application_Base;

/**
 * アプリケーションの定義
 */
class Application extends Application_Base
{
    /**
     * Webアプリケーションの開始処理
     */
    public function startWebapp ()
    {
        $route = route()->getCurrentRoute();

        // Pageに対応するControllerクラスの特定
        list($controller_name, $action_name) = explode('.',$route->getPage(),2);
        $controller_class_name = "R\\App\\Controller\\".str_camelize($controller_name)."Controller";
        $action_method_name = "act_".$action_name;
        if ( ! method_exists($controller_class_name, $action_method_name)) {
            report_error("RoutingのPage設定に対応するActionがありません",array(
                "action_method_call" => $controller_class_name."::".$action_method_name,
            ));
        }

        // 認証処理
        if ($auth = $controller_class_name::getAuthenticate()) {
            auth()->authenticate($auth["access_as"], $auth["priv_required"]);
        }

        // Actionメソッドの呼び出し
        $controller = new $controller_class_name();
        $controller->$action_method_name();

        // テンプレートファイルの特定
        $request_file = $route->getFile();

        // JSONデータを返す
        if (preg_match('!\.json$!',$request_file)) {
            response()->output(array(
                "data" => json_encode((array)response()),
                "content_type" => "application/json; charset=utf-8",
            ));
        // Smartyテンプレートの読み込み
        } else {
            if ( ! file_exists($request_file)) {
                report_warning("テンプレートファイルがありません",array(
                    "request_file" => $request_file,
                    "route" => $route,
                ));
                response()->error("File Not Found", array(
                    "request_file" => $request_file,
                ), 404);
                return;
            }
            try {
                $smarty = new \R\Lib\Smarty\SmartyExtended();
                $smarty->assign((array)response());
                $smarty->assign("forms", form()->getRepositry($controller_class_name));
                response()->output(array(
                    "data" => $smarty->fetch($request_file),
                    "content_type" => "text/html; charset=utf-8",
                ));
            } catch (\SmartyException $e) {
                report_error("[SmartyError] ".$e->getMessage(),array(
                    "request_file" => $request_file,
                    "route" => $route,
                ),array(
                    "exception" =>$e,
                ));
            }
        }
    }
    /**
     * Webアプリケーションの終了処理
     */
    public function endWebapp ()
    {
        $output = response()->getCleanOutput();
        report("Webアプリケーション終了",array(
            "route" => route()->getCurrentRoute(),
            "output_mode" => ! $output ? "void" : ( ! $output["mode"] ? "normal" : $output["mode"]),
        ));
        if (app()->getDebugLevel()) {
            print response()->getCleanReportBuffer();
        }
        // 出力処理
        if ($output) {
            // エラー応答
            if ($output["mode"] == "error") {
                header("HTTP/1.1 ".$output["response_code"]." ".$output["response_code_msg"]);
                if ($error_php = app()->config("Config.error_document.".$response_code)) {
                    include($error_php);
                }
            // 転送応答
            } elseif ($output["mode"] == "redirect") {
                $url = $output["url"];
                if (app()->getDebugLevel()) {
                    $redirect_link_html ='<div style="padding:20px;'
                        .'background-color:#f8f8f8;border:solid 1px #aaaaaa;">'
                        .'Redirect ... '.$url.'</div>';
                    print tag("a",array("href"=>$url),$redirect_link_html);
                } else {
                    header("Location: ".$url);
                }
            // データ出力応答
            } else {
                // HTML表示以外の出力であればバッファを消去
                if ( ! preg_match('!^text/html!i',$output["content_type"])) {
                    while (ob_get_level()) {
                        ob_get_clean();
                    }
                }
                // Content-Typeヘッダの送信
                if (isset($output["content_type"])) {
                    header("Content-Type: ".$output["content_type"]);
                } elseif (isset($output["download"])) {
                    header("Content-Type: application/octet-stream");
                }
                // ダウンロードファイル名の指定
                if (isset($output["download"])) {
                    if (is_string($output["download"])) {
                        header("Content-Disposition: attachment; filename=".$output["download"]);
                    } elseif (isset($output["file"])) {
                        header("Content-Disposition: attachment; filename=".basename($output["file"]));
                    }
                }
                if (isset($output["data"])) {
                    print $output["data"];
                } elseif (isset($output["file"])) {
                    if (is_readable($output["file"])) {
                        readfile($output["file"]);
                    }
                } elseif (isset($output["stored_file"])) {
                    if (is_a($output["stored_file"], "R\\Lib\\FileStorage\\StoredFile")) {
                        $output["stored_file"]->download();
                    }
                }
            }
        }
    }
    /**
     * アプリケーションの初期化処理
     */
    public function init ()
    {
        // Webアプリケーションの初期化処理
        if (php_sapi_name() != 'cli') {
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
            // HTTPパラメータ構築
            $request_values = array_merge($_GET, $_POST);
            mb_convert_variables("UTF-8","UTF-8",$request_values);
            $request_values = sanitize($request_values);
            $_REQUEST = $request_values;
            // 出力バッファの設定
            ob_start(function($out) {
                //$out = mb_convert_encoding($out,"UTF-8","UTF-8");
                return $out;
            });
            // Composer未対応クラスの互換読み込み処理
            spl_autoload_register("load_class");
            start_dync();
            obj("Rdoc")->check();
        }
        // 終了処理
        set_error_handler(function($errno, $errstr, $errfile=null, $errline=null, $errcontext=null) {
            report($errstr,$errcontext,array(
                "errno" =>$errno,
                "errstr" =>$errstr,
                "errfile" =>$errfile,
                "errline" =>$errline,
            ));
        },E_ALL);
        set_exception_handler(function($e) {
            app()->ending();
            report("[Uncaught ".get_class($e)."] ".$e->getMessage(),array(
                "exception" =>$e,
            ),array(
                "errno" =>E_ERROR,
                "exception" =>$e,
            ));
        });
        register_shutdown_function(function() {
            app()->ending();
            // FatalErrorによる強制終了
            $error = error_get_last();
            if ($error && ($error['type'] == E_ERROR || $error['type'] == E_PARSE
                || $error['type'] == E_CORE_ERROR || $error['type'] == E_COMPILE_ERROR)) {
                try {
                    report("[Fatal] ".$error["message"] ,$error ,array(
                        "type" =>"error_handler",
                        "errno" =>$error['type'],
                        "errstr" =>"Fatal Error. ".$error['message'],
                        "errfile" =>$error['file'],
                        "errline" =>$error['line'],
                    ));
                } catch (ApplicationEndingException $e) {
                }
            }
        });
    }
    /**
     * アクセスしている端末の種類を取得する
     */
    public function getAccessDevice ()
    {
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $ua_list = array(
            'mobile' => array(
                'iphone'     => '!iPhone|iPod!',
                'android'    => '!Android.*?Mobile!',
                'softbank'   => '!J-PHONE|Vodafone|MOT-|SoftBank!i',
                'docomo'     => '!DoCoMo!i',
                'au'         => '!UP\.Browser|KDDI!i',
            ),
            'tablet' => array(
                'ipad'       => '!iPad!',
                'androidtab' => '!Android!',
            ),
        );
        foreach ($ua_list as $category => $ua_list_detail) {
            foreach ($ua_list_detail as $type => $pattern) {
                if (preg_match($pattern, $ua)) {
                    return $category;
                }
            }
        }
        return "pc";
    }
}