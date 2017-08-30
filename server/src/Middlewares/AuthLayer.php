<?php

namespace FsaTechTest\Middlewares;

/**
 * a *very* simple example to illustrate an auth middleware
 * if the incoming request has the correct token, we process them through
 * if they dont, we 403 them
 */
class AuthLayer
{
    public function __invoke($req, $res, $next)
    {
        $authToken = $req->getHeader('Authorization')[0];
        if ($authToken !== 'TOKEN-HERE') {
            return $res->withJson([
                'error' => 'Incorrect authorization token!'
            ], 403);
        }

        $res = $next($req, $res);

        return $res;
    }
}
