<?php
use R\Lib\Controller\Controller_Base;

/**
 * 親Controller
 */
class Controller_App extends Controller_Base
{
    protected $access_as = null;
    protected $priv_required = false;

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

    /**
     * act_*前処理
     */
    public function before_act ()
    {
        parent::before_act();

        $this->before_act_force_https();
        $this->before_act_protect_against_csrf();

        // 認証処理
        auth()->authenticate($this->access_as, $this->priv_required);

        // リクエスト変換処理
        obj("LayoutRequestArray")->fetch_request_array();
    }

    /**
     * act_*後処理
     */
    public function after_act ()
    {
        parent::after_act();
    }

    /**
     * HTTPアクセス制限
     */
    protected function before_act_force_https ()
    {
        $request_path =registry("Request.request_path");

        // HTTPS/HTTPアクセス制限の設定解決
        if ($force_https =registry("Routing.force_https.zone")) {

            $is_https =$_SERVER["HTTPS"];
            $is_force_https =in_path($request_path,$force_https);
            $is_safe =in_path($request_path,registry("Routing.force_https.safe_zone"));

            // 転送不要 safe_zone
            if ($is_safe) {

            // HTTPSへ転送
            } elseif ($is_force_https && ! $is_https) {

                $redirect_url =path_to_url($request_path,"https");
                $redirect_url =url($redirect_url,$_GET);

                redirect($redirect_url);

            // HTTPへ転送
            } elseif ( ! $is_force_https && $is_https) {

                $redirect_url =path_to_url($request_path,true);
                $redirect_url =url($redirect_url,$_GET);

                redirect($redirect_url);
            }
        }
    }

    /**
     * CSRF対策
     */
    protected function before_act_protect_against_csrf ()
    {
        $config =registry("Security.csrf");
        $protect_pages =$config["protect_pages"];

        $path =registry("Request.request_path");
        $pac_ticket =substr(md5(session_id()),3,6);

        if ($protect_pages) {

            if (in_path($path,$protect_pages) && $_REQUEST["_pac_ticket"] !== $pac_ticket) {

                report_error("PA-CSRF ticket error",array(
                    "pac_ticket" =>$pac_ticket,
                    "request_pac_ticket" =>$_REQUEST["_pac_ticket"],
                ));
            }

            add_url_rewrite_rule($protect_pages, "_pac_ticket", $pac_ticket);
        }
    }
}
