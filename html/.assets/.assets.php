<?php
    return array(
        "import" => array(
            "lib"
        ),
        "ext" => array(
        ),
        "local" => array(
            "app.common" => array("app/common.js"),
            "app.observeForm" => array("app/observeForm.js",
                array("jquery", "util.appendStyle", "util.FormObserver")),
            "app.showFlash" => array("app/showFlash.js", array("config.toastr")),
        ),
    );
