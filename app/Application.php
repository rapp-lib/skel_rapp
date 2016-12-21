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
    protected function startWebapp ()
    {
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
        start_dync();
        // HTTPパラメータ構築
        $request_values = $this->escapeHtml(array_merge($_GET,$_POST));
        $this->request()->exchangeArray($request_values);
        // 出力バッファの設定
        ob_start();
        // Routeに対応する処理の実行
        $route = route()->getCurrentRoute();
        $page = $route->getPage();
        if ( ! $page) {
            report_error("リクエストに対応するPage設定がありません",array(
                "route" => $route,
            ));
        }
        if ($controller = \R\Lib\Webapp\Controller_Base::invokeAction($page)) {
            $vars = $controller->getVars();
            $this->response()->exchangeArray($vars);
        }
        $request_file = $route->getFile();
        // 出力が決定済みであれば終了
        if ($this->response()->hasOutput()) {
        // .jsonに対するリクエストであればJSONデータを返す
        } elseif (preg_match('!\.json$!',$request_file)) {
            $this->response()->output(array(
                "data" => json_encode((array)$this->response()),
                "content_type" => "application/json; charset=utf-8",
            ));
        // .htmlに対するリクエストであればSmartyテンプレートを読み込む
        } elseif (preg_match('!\.html$!',$request_file)) {
            if ( ! file_exists($request_file)) {
                $this->response()->error("File Not Found", array(
                    "request_file" => $request_file,
                ), 404);
                return;
            }
            $smarty = new \R\Lib\Smarty\SmartyExtended();
            $smarty->assign((array)$this->response());
            $html = $smarty->fetch($request_file);
            $this->response()->output(array(
                "data" => $html,
                "content_type" => "text/html; charset=utf-8",
            ));
        }
    }
    /**
     * Webアプリケーションの終了処理
     */
    protected function endWebapp ()
    {
        $output = $this->response()->getCleanOutput();
        report("実行終了",array(
            "route" => route()->getCurrentRoute(),
            "request" => $this->request(),
            "response" => $this->response(),
            "output_mode" => ! $output ? "none" : ( ! $output["mode"] ? "normal" : $output["mode"]),
        ));
        // 出力処理
        if ($output) {
            // エラー応答
            if ($output["mode"] == "error") {
                $this->response_code = isset($output["response_code"]) ? $output["response_code"] : 500;
                header("HTTP", true, $this->response_code);
                if ($error_php = app()->config("Config.error_document.".$this->response_code)) {
                    include($error_php);
                }
            // 転送応答
            } elseif ($output["mode"] == "redirect") {
                $url = $output["url"];
                if (app()->getDebugLevel()) {
                    print tag("a",array("href"=>$url),'<div style="padding:20px;'
                        .'background-color:#f8f8f8;border:solid 1px #aaaaaa;">'
                        .'Redirect ... '.$url.'</div>');
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
     * 再帰的なHTMLエスケープ
     */
    private function escapeHtml ($value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = $this->escapeHtml($v);
            }
        } elseif (is_string($value)) {
            $value = htmlspecialchars($value, ENT_QUOTES);
        }
        return $value;
    }
}