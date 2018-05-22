<?php
    return array(
        "http.webroots.www" => array(
            "base_uri" => "",
            "routes" => array(
                array("file.index", "/.file/{storage}/{id:.+}"),
            ),
            "middlewares" => array(
                100 => function($request, $next) {
                    // ErrorFallback
                    try {
                        return $next($request);
                    } catch (\Exception $e) {
                        $app->report->logException($e);
                    }
                    return $app->http->response("error");
                },
                150 => function($request, $next) {
                    // SessionHandle
                    return app()->session->handle($request, $next);
                },
                151 => function($request, $next) {
                    // SwitchRole
                    $role = $request->getUri()->getPageAuth()->getRole();
                    app()->user->switchRole($role);
                    return $next($request);
                },
                152 => function($request, $next) {
                    // SwitchLocale
                    $locale = $request->getUri()->getRouteParam("locale");
                    if ($locale) app()->i18n->setLocale($locale);
                    return $next($request);
                },
                350 => function($request, $next) {
                    // AuthenticateFirewall
                    $role = $request->getUri()->getPageAuth()->getRole();
                    return app()->user->firewall($role, $request, $next);
                },
                360 => function($request, $next) {
                    // CsrfTokenCheck
                    if ($request->getUri()->getRouteParam("csrf_check")) {
                        $input = $request->getAttribute(R\Lib\Http\InputValues::ATTRIBUTE_INDEX);
                        if ($input[app()->security->getCsrfTokenName()] != app()->security->getCsrfToken()) {
                            return app()->http->response("forbidden");
                        }
                    }
                    return $next($request);
                },
            ),
        ),
    );