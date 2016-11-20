<?php

/**
 * App.php - created 20 Nov 2016 10:25:44
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

use Bramus\Monolog\Formatter;
use \Exception;
use Silex\Application;
use Silex\Provider;
use Symfony\Component\Config;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpKernel;
use Symfony\Component\Routing;

$app = new \Silex\Application();
$app['application_env'] = \Tronald\Lib\Util::getEnvOrDefault('APPLICATION_ENV', 'prod');
$app['debug']           = 'prod' === $app['application_env']  ? false : true;

$app->extend('routes', function (Routing\RouteCollection $routes, Application $app)
{
    $loader = new Routing\Loader\YamlFileLoader(
        new Config\FileLocator(APPLICATION_PATH . '/config/routes/api')
    );

    $collection = $loader->load('index.yml');
    $routes->addCollection($collection);

    return $routes;
});

$app->register(new \Tronald\App\Api\Provider\ConfigProvider());
$app->register(new \Tronald\App\Api\Provider\ServiceProvider());
$app->register(new \Tronald\App\Api\Provider\SlackProvider());

$streamHandler = new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::INFO);
$streamHandler->setFormatter(
    new Formatter\ColoredLineFormatter(new Formatter\ColorSchemes\TrafficLight)
);
$app->register(
    new Provider\MonologServiceProvider(),
    [
        'monolog.name'    => 'tronald_dump_api',
        'monolog.handler' => $streamHandler
    ]
);

$app->error(function (Exception $exception, HttpFoundation\Request $request) use ($app) {
    $data = [
        'status'  => 'undefined',
        'message' => $exception->getMessage()
    ];

    if ($exception instanceof HttpKernel\Exception\HttpException) {
        $data['status'] = $exception->getStatusCode();
    }

    if ($app['debug']) {
        $data['trace'] = $exception->getTrace();
    }

    return new Tronald\Lib\Http\JsonResponse($data);
});

return $app;
