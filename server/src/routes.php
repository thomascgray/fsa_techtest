<?php

use FsaTechTest\Middlewares\AuthLayer as AuthLayer;
use FsaTechTest\Middlewares\CacheLayer as CacheLayer;
use FsaTechTest\Controllers\FSAController as FSAController;

$cacheTime = \Env::get('RESPONSE_CACHE_TIME');

$app->group('/v1', function () use ($app, $cacheTime) {

    /**
     * an example of an authenticate endpoint. this would take a user/pass,
     * some kind of login credentials, etc. and return an expire-able authentication
     * token to be sent along with every subsequent request
     */
    $app->post('/authenticate', function($request, $response, $args) {
        return $response->withStatus(200)->withJson([
            'token' => 'TOKEN-HERE'
        ]);
    });

    $app->get('/local-authorities', FSAController::class . ":listLocalAuthorities")
        ->add(new AuthLayer())
        ->add(new CacheLayer($cacheTime));

    $app->get('/establishments-profile/{localAuthorityId:[0-9]+}', FSAController::class . ":getEstablishmentsProfilePerLocalAuthority")
        ->add(new AuthLayer())
        ->add(new CacheLayer($cacheTime));

});

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->withHeader('Access-Control-Request-Method', '*');
});
