<?php
    $GLOBAS["__BOOTSTRAP_CONFIG__"] =array(
        "WEBAPP" => "1",
        "DOCUMENT_ROOT_DIR" => realpath("./"),
        "RELATIVE_HTML_DIR" => "",
        "REQUEST_URI" => $_SERVER['REQUEST_URI'],
    );
    require_once __DIR__."/../webapp-bootstrap.php";
