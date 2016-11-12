<?php

/**
 * index.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use Bramus\Monolog\Formatter;
use Silex\Application;
use Silex\Provider;
use Symfony\Component\Config;
use Symfony\Component\Routing;

$app = new \Silex\Application();
$app['application_env'] = \Tronald\Lib\Util::getEnvOrDefault('APPLICATION_ENV', 'prod');
$app['debug']           = 'prod' === $app['application_env']  ? false : true;

$app->extend('routes', function (Routing\RouteCollection $routes, Application $app)
{
    $loader = new Routing\Loader\YamlFileLoader(
        new Config\FileLocator(APPLICATION_PATH . '/config/routes')
    );

    $collection = $loader->load('index.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->register(new \Tronald\App\Web\Provider\ConfigProvider());
$app->register(new \Tronald\App\Web\Provider\BrokerProvider());
$app->register(new Provider\CsrfServiceProvider());
$app->register(new Provider\FormServiceProvider());
$app->register(new Provider\LocaleServiceProvider());
$app->register(new Provider\TranslationServiceProvider(), [
    'locale_fallbacks'   => ['en'],
    'translator.domains' => []
]);
$app->register(new Provider\ValidatorServiceProvider());

$streamHandler = new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::INFO);
$streamHandler->setFormatter(
    new Formatter\ColoredLineFormatter(new Formatter\ColorSchemes\TrafficLight)
);
$app->register(
    new Provider\MonologServiceProvider(),
    [
        'monolog.name'    => 'tronald_dump_web',
        'monolog.handler' => $streamHandler
    ]
);

$app->register(new Provider\TwigServiceProvider(), [
    'twig.path'    => MODULE_PATH . '/resource/view/'
]);

if ($app['twig'] instanceof Twig_Environment) {
    $app['twig']->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
        return sprintf('%s/%s', $app['config']['asset']['url'], ltrim($asset, '/'));
    }));
}

$app->error(function (\Exception $exception, $httpStatusCode) use ($app) {
    if ($app['debug']) {
        return;
    }
});

return $app;
