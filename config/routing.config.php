<?php
    app()->router->getWebroot("www")->addRouting(array(
        "service.file" => "/file:/*",
    ));
