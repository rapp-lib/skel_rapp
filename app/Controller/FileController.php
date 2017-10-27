<?php
namespace R\App\Controller;

/**
 * @controller
 */
class FileController extends Controller_App
{
    private $mimes = array(
        "pdf" => "application/pdf",
        "jpg" => "image/jpeg",
        "jpeg" => "image/jpeg",
        "png" => "image/png",
        "gif" => "image/gif",
    );
    /**
     * @page
     * @title ファイルダウンロード
     */
    public function act_index ()
    {
        $uri = $this->request->getUri();
        $file = app()->file->getFileByUri($uri);
        $stream = $file ? $file->getSource() : null;
        $stream = file_exists($stream) ? $stream : null;
        if ( ! $stream) return app()->http->response("notfound");
        $headers = array();
        $ext = preg_match('!\.([^\.]+)$!', $stream, $m) ? strtolower($m[1]) : "";
        $headers["content-type"] = $this->mimes[$ext] ?: "application/octet-stream";
        return app()->http->response("stream", $stream, array("headers"=>$headers));
    }
}
