<?php
    $config = include(constant("R_LIB_ROOT_DIR")."/assets/docs/doc_config.php");
    $config["overwrite_config"] = array(
        "http.webroots.www.base_dir"=>constant("R_APP_ROOT_DIR")."/html",
    );
    return $config;
