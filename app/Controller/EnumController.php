<?php
namespace R\App\Controller;

/**
 * @controller
 */
class EnumController extends Controller_App
{
    /**
     * @page
     */
    public function act_json ()
    {
        $allow = array(
        );
        $enum = $this->input["enum"];
        $key = $this->input["key"];
        if ( ! $key) report_error("keyの指定は必須です");
        if ( ! in_array($enum, $allow)) report_error("許可されていないenumです");
        return app("http")->response("json", app()->enum[$enum][$key]);
    }
}
