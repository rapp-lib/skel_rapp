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
        if ( ! in_array($enum, $allow)) return app()->http->response("forbidden");
        if ( ! strlen($key)) return app("http")->response("json", array());
        $value = app()->enum[$enum][$key];
        if (is_array($value)) $value = \R\Lib\Util\Arr::kvdict($value);
        return app("http")->response("json", $value);
    }
}
