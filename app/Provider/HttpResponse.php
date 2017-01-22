<?php
namespace R\App\Provider;

class HttpResponse extends \R\Lib\Core\Response
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
    public function render ()
    {
        $output = $this->output;
        report("実行終了",array(
            "route" => app()->router()->getCurrentRoute(),
            "request" => app()->request(),
            "response" => $this,
        ));
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
