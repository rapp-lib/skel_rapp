<?php
    return array(
        "http.webroots.www" => array(
            "base_uri" => "",
            "middlewares" => array(
                150 => function($request, $next) {
                    // SessionStart
                    app()->session->start();
                    $response = $next($request);
                    return $response;
                },
                151 => function($request, $next) {
                    // SwitchRole
                    $role = $request->getUri()->getPageAuth()->getRole();
                    app()->user->switchRole($role);
                    return $next($request);
                },
                350 => function($request, $next) {
                    // AuthenticateFirewall
                    $role = $request->getUri()->getPageAuth()->getRole();
                    return app()->user->firewall($role, $request, $next);
                },
            ),
            "assets_catalog_uris"=>array(
                "path://.assets/lib/.assets.php",
                "path://.assets/app/.assets.php",
            ),
        ),
    );