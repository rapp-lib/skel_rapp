<?php
    ini_set("display_errors",false);
    error_reporting(E_ALL&~E_NOTICE);

    $GLOBALS["_composer_loader"] = require_once __DIR__."/../vendor/autoload.php";

    // システム動作基本設定
    $webapp_dir = __DIR__."/..";
    $tmp_dir = $webapp_dir."/tmp";
    $html_dir = $webapp_dir."/html";
    $document_root_dir = $html_dir;
    $document_root_url = "";

    registry(array(
        "Path.webapp_dir" =>$webapp_dir,
        "Path.tmp_dir" =>$tmp_dir,
        "Path.html_dir" =>$html_dir,
        "Path.document_root_dir" =>$document_root_dir,
        "Path.document_root_url" =>$document_root_url,

        // DB接続
        "DBI.connection" =>array(
            "default" =>array(
                'driver' => 'mysql',
                'encoding' => 'utf8',
                'persistent' => true,
                'prefix' => '',
                'host' => 'localhost',
                'database' => 'test',
                'login' => 'dev',
                'password' => 'pass',
            ),
        ),
        "DBI.statement.default_join_type" =>"LEFT",
        "DBI.fetch_col_name_include_table" =>false,

        // エラー時転送先
        "Config.error_document" =>array(
            "404" =>$html_dir."/.assets/error_doc/404.php",
            "500" =>$html_dir."/.assets/error_doc/500.php",
        ),

        // 内部設定
        "Config.external_charset" =>"UTF-8",
        "Config.session_lifetime" =>86400,
        "Config.cake_lib" =>"rlib_cake2",
        "Config.auto_deploy" =>true,
        "Config.dync_key" =>"_",
        "Config.webapp_include_path" =>array(
            "app",
            "app/controller",
            "app/include",
            "app/list",
        ),

        // デバッグ
        "Report.force_reporting" =>false,
        "Report.error_reporting" =>E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED,
        "Report.buffer_enable" =>false,
        "Report.output_to_file" =>null,
        "Report.report_about_dync" =>false,
        "Report.report_backtraces" =>false,

        // ファイルアップロード
        "UserFile.group" =>array(
            "public" =>array(
                "upload_dir" =>$html_dir.'/.assets/user_file/uploaded',
                "allow_ext" =>array(
                    'jpg', 'jpeg', 'png', 'gif', 'pdf',
                ),
                "hash_level" =>3,
                "save_raw_filename" =>false,
            ),
            "private" =>array(
                "upload_dir" =>$tmp_dir.'/uploaded',
                "allow_ext" =>array(
                    'jpg', 'jpeg', 'png', 'gif',
                    'zip', 'pdf', 'bmp',
                    'csv', 'txt', 'xml',
                    'ppt', 'pptx', 'doc', 'docx', 'xls', 'xlsx',
                ),
                "hash_level" =>3,
                "save_raw_filename" =>false,
            ),
        ),

        // CSRF対策
        "Security.csrf.protect_pages" => array(),

        // ルーティング
        "Routing.page_to_path" =>array(),

        // HTTPSアクセス強制
        "Routing.force_https.zone" =>array(),
        "Routing.force_https.safe_zone" =>array(),

        // 設置環境別設定
        //"Config.envs.dev.overwrite_config" =>array(),

        // 呼び出し元別設定
        //"Config.loaders.default.overwrite_config" =>array(),
    ));

    // 各種設定読み込み
    foreach (glob(dirname(__FILE__).'/*.config.php') as $config_file) {
        include_once($config_file);
    }

    // 環境別設定の上書き
    foreach ((array)glob(dirname(__FILE__).'/*.env-ident') as $env_ident_file) {
        if (preg_match('!/([^\./]+)\.env-ident$!',$env_ident_file,$match)) {
            $env_id =$match[1];
            registry("Config.env.env_id", $env_id);

            $env_config =registry("Config.envs.".$env_id.".overwrite_config");
            registry(array_escape((array)$env_config));
            break;
        }
    }

    // 読み込み元別設定の上書き
    if (getenv("LOADER_ID")) {
        $loader_id =getenv("LOADER_ID");
        registry("Config.loader.loader_id", $loader_id);

        $loader_config =registry("Config.loaders.".$loader_id.".overwrite_config");
        registry(array_escape((array)$loader_config));
    }
