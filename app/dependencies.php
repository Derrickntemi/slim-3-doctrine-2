<?php
// DIC configuration

use App\Action\RoleAction;
use App\Action\ConstituencyAction;
use App\Action\CountyAction;
use App\Action\UserAction;


$container = $app->getContainer();

// -----------------------------------------------------------------------------
// Service providers
// -----------------------------------------------------------------------------

//Override the default Not Allowed Handler
$container['notAllowedHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withJSON(
                array('error' => 'Method missing, please refer to the documentation' )
            );
    };
};

//Override the default Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->withStatus(404)
            ->withJSON(
                array('error' => 'Invalid route, please refer to the documentation' )
            );
    };
};
// Twig
$container['view'] = function ($c) {
    $settings = $c->get('settings');
    $view = new \Slim\Views\Twig($settings['view']['template_path'], $settings['view']['twig']);

    // Add extensions
    $view->addExtension(new Slim\Views\TwigExtension($c->get('router'), $c->get('request')->getUri()));
    $view->addExtension(new Twig_Extension_Debug());

    return $view;
};

// Flash messages
$container['flash'] = function ($c) {
    return new \Slim\Flash\Messages;
};

/**
 * JWT Token
 */
 $container['jwtoken'] = function($c)
{
    return new \Firebase\JWT\JWT();
};
// -----------------------------------------------------------------------------
// Service factories
// -----------------------------------------------------------------------------

/**
 * @param $c
 * @return \Monolog\Logger
 */
// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings');
    $logger = new \Monolog\Logger($settings['logger']['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['logger']['path'], \Monolog\Logger::DEBUG));
    return $logger;
};
/**
 * @param $c
 * @return \Doctrine\ORM\EntityManager
 */
// Doctrine
$container['em'] = function ($c) {
    $settings = $c->get('settings');
    $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
        $settings['doctrine']['meta']['entity_path'],
        $settings['doctrine']['meta']['auto_generate_proxies'],
        $settings['doctrine']['meta']['proxy_dir'],
        $settings['doctrine']['meta']['cache'],
        false
    );
    return \Doctrine\ORM\EntityManager::create($settings['doctrine']['connection'], $config);
};

// -----------------------------------------------------------------------------
// Action factories
// -----------------------------------------------------------------------------

$container[UserAction::class] = function($c)
{
    $userResource = new App\Resource\UserResource($c->get('em'));
    $countyResource = new App\Resource\CountyResource($c->get('em'));
    $constituencyResource = new App\Resource\ConstituencyResource($c->get('em'));
    $roleResource = new App\Resource\RoleResource($c->get('em'));
    return new App\Action\UserAction($userResource,$countyResource,$constituencyResource,$roleResource,$c->get('logger'));
};
$container[CountyAction::class] = function ($c)
{
    $countyResource = new App\Resource\CountyResource($c->get('em'));
    return new App\Action\CountyAction($countyResource,$c->get('logger'));

};

$container[ConstituencyAction::class] = function ($c)
{
    $constituencyResource = new App\Resource\ConstituencyResource($c->get('em'));
    return new App\Action\ConstituencyAction($constituencyResource,$c->get('logger'));
};
$container[RoleAction::class] = function($c)
{
    $roleResource = new App\Resource\RoleResource($c->get('em'));
    return new App\Action\RoleAction($roleResource,$c->get('logger'));
};