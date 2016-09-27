<?php
	
	ini_set("display_errors",true);
	error_reporting(E_ALL&~E_NOTICE);
	
	require_once __DIR__."/../vendor/autoload.php";
	
	//-------------------------------------
	// $B%7%9%F%`F0:n4pK\@_Dj(B
	registry("Path.webapp_dir", realpath(dirname(__FILE__)."/.."));
	registry(array(
	
		// $B%Q%9@_Dj(B
		"Path.tmp_dir" =>registry("Path.webapp_dir")."/tmp",
		"Path.html_dir" =>registry("Path.webapp_dir")."/html",
		"Path.document_root_dir" =>registry("Path.webapp_dir")."/html",
		"Path.document_root_url" =>"",
		
		// $B4pK\@_Dj(B
		"Config.dync_key" =>"_",
		"Config.auto_deploy" =>false,
		"Config.external_charset" =>"UTF-8",
		"Config.session_lifetime" =>86400,
		"Config.webapp_include_path" =>array(
			"app",
			"app/include",
			"app/controller",
			"app/context",
			"app/list",
			"app/model",
		),
		
		// php.ini$B@_Dj(B
		"Config.php_ini" =>array_escape(array(
		)),
		
        // cake$B@_Dj(B
        "Config.cake_lib" =>"rlib_cake2",
        
		// $B%G%P%C%0@_Dj(B
		"Report.error_reporting" =>E_ALL&~E_NOTICE&~E_STRICT&~E_DEPRECATED,
		"Report.buffer_enable" =>false,
		"Report.output_to_file" =>null,
		"Report.report_about_dync" =>false,
		"Report.report_backtraces" =>false,
	));
	
	registry(array(
		
		// $B%(%i!<;~E>Aw@h@_Dj(B
		"Config.error_document" =>array(
			"404" =>registry("Path.html_dir")."/errors/404.html",
			"500" =>registry("Path.html_dir")."/errors/500.html",
		),
		
		// $BJ#?t%5%$%HBP1~@_Dj(B
		"Config.vhosts" =>array(
		),
	));

	//-------------------------------------
	// $B3F<o@_DjFI$_9~$_(B
	foreach (glob(dirname(__FILE__).'/*.config.php') as $config_file) {
		
		include_once($config_file);
	}
	
	//-------------------------------------
	// $B4D6-JL@_Dj$N>e=q$-(B
	foreach ((array)glob(dirname(__FILE__).'/*.env-ident') as $env_ident_file) {
		
		if (preg_match('!/([^\./]+)\.env-ident$!',$env_ident_file,$match)) {
			
			$env_id =$match[1];
			
			registry(array(
				"Config.env.env_id" =>$env_id,
			));
			
			$env_config =registry("Config.envs.".$env_id.".overwrite_config");
			
			registry(array_escape((array)$env_config));
			
			break;
		}
	}
	
	//-------------------------------------
	// $BFI$_9~$_85JL@_Dj$N>e=q$-(B
	if (getenv("LOADER_ID")) {
		
		$loader_id =getenv("LOADER_ID");
		
		registry(array(
			"Config.loader.loader_id" =>$loader_id,
		));
		
		$loader_config =registry("Config.loaders.".$loader_id.".overwrite_config");
		
		registry(array_escape((array)$loader_config));
	}
	