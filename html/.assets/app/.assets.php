<?php
    if ( ! $asset) { return; }

    // adm - admテンプレート向けJS/CSS
    $asset->registerJsUrl("app.adm.common-js", $url."/adm/js/common.js")
        ->required("app.adm.base-css")
        ->required("jquery:*");
    $asset->registerCssUrl("app.adm.base-css", $url."/adm/css/base.css")
        ->required("bs.font-awesome");

    // show-errors - エラー表示
    $asset->registerJsUrl("app.show-errors", $url."/show-errors/js/show-errors.js")
        ->required("app.show-errors.css")
        ->required("rui.show-errors");
    $asset->registerCssUrl("app.show-errors.css", $url."/show-errors/css/show-errors.css");

    // bs.font-awesome
    $asset->registerCssUrl("bs.font-awesome:4.3.0", "//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css");
