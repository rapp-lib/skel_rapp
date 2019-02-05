<?php
namespace R\App\View;

use R\Lib\View\SmartyView;

class View_App extends SmartyView
{
    public function makeSmarty ()
    {
        $smarty = parent::makeSmarty();
        // $smarty->registerFilter("pre", array(get_class($this), "smarty_prefilter_include"));
        $smarty->registerPlugin("modifier","filesize" ,array(get_class($this), "smarty_modifier_filesize"));
        // $smarty->clearAllCache();
        return $smarty;
    }

    public static function smarty_modifier_filesize  ($filepath,$params)
    {
        if (! $filepath) return false;
        if (! $params["storage"]) $params["storage"] ="public";
        $file = app()->file->getStorage($params["storage"])->getFileByUri($filepath);
        $stream = $file ? $file->getSource() : null;
        $size ="0";
        $unit ="Byte";
        if($stream){
            $size =filesize($stream);
            if ($size < 100) {
                $size = floor($size * 10) / 10;
            } elseif ($size / 1024 < 100) {
                $size = floor($size / 1024 * 10) / 10;
                $unit ="KB";
            } else {
                $size = floor($size / 1024 / 1024 * 10) / 10;
                $unit ="MB";
            }
        }
        return $size.$unit;
    }
}
