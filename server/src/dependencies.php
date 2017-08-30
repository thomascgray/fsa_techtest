<?php
// DIC configuration

use \Slim\HttpCache\CacheProvider as CacheProvider;

$container = $app->getContainer();

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

$container['cache'] = function () {
    return new CacheProvider();
};

$dotenv = new \Dotenv\Dotenv(realpath(__DIR__ . '/../'));
$dotenv->load();
