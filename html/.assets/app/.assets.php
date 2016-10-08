<?php
    if ( ! $asset) { return; }

    $asset->bufferJsCode('rui.init("'.$url.'");')
        ->required("rui.require");

    $asset->registerJsUrl("app.common-script", "1", $url."/js/sys-app.js");
    $asset->registerCssUrl("app.common-css", "1", $url."/css/default-admin.css")
        ->required("bs.font-awesome-css");

    $asset->registerCssUrl("bs.font-awesome-css", "4.3.0", "//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
