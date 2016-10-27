<?php

/**
 * 親Controller
 */
class Controller_App extends Controller_Base
{
    protected static $access_as = null;
    protected static $priv_required = false;

    /**
     * 認証処理
     */
    public function authenticate ()
    {
        auth()->authenticate(static::$access_as, static::$priv_required);
    }

    /**
     * メール送信
     */
    protected function send_mail ($options)
    {
        if ($options["template"] && ! $options["template_file"]) {
            $options["template_file"] =registry("Path.webapp_dir")."/mail/".$options["template"].".php";
        }
        if ($options["vars"] && ! $options["template_options"]) {
            $options["template_options"] =$options["vars"];
        }
        /*
        $options["send_mode"] ="smtp";
        $options["send_options"] =array(
                "host" =>"example.com",
                "port" =>"587",
                "auth" =>true,
                "username" =>"info@example.com",
                "password" =>"password",
        );
        */
        obj("BasicMailer")->send_mail($options);
    }
}
