<?php
namespace R\App\Controller;

/**
 * @controller
 */
class ServiceController extends Controller_App
{
    /**
     * @page
     * @title FileStorageに保存されたファイルのダウンロード
     */
    public function act_file ()
    {
        $path = route()->getCurrentRoute()->getPath();
        $code = preg_replace('!^/file:!','',$path);
        response()->output(array("stored_file"=>file_storage()->get($code)));
    }
}
