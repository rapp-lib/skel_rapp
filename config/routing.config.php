<?php
    webroot("www")->addRouting(array(
      "include.static" => "/include/*",
      "service.file" => "/file:/*",
    ));
