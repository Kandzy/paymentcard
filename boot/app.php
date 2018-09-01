<?php
/**
 * Created by PhpStorm.
 * User: dkliukin
 * Date: 8/2/18
 * Time: 11:43 AM
 */

require __DIR__.'/../vendor/autoload.php';
session_start();

$app = new \Slim\App([
        'settings' => [
            'displayErrorDetails' => true,
        ]
    ]
);

$container = $app->getContainer();


$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/View/', [
        'cache' => false
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container->get('router'), $basePath));

    return $view;
};

//include "databaseInstall.php";


require __DIR__."/../routs/web.php";
