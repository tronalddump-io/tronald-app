<?php

/**
 * index.php - created 19 Nov 2016 21:57:07
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

$container = new \Silex\Application();
$container->register(new \Tronald\App\Cli\Provider\ConfigProvider());
$container->register(new \Tronald\App\Cli\Provider\BrokerProvider());
$container->register(new \Tronald\App\Cli\Provider\CommandProvider());

$streamHandler = new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::INFO);
$streamHandler->setFormatter(
    new Formatter\ColoredLineFormatter(new Formatter\ColorSchemes\TrafficLight)
);
$container->register(
    new Provider\MonologServiceProvider(),
    [
        'monolog.name'    => 'tronald_dump_console',
        'monolog.handler' => $streamHandler
    ]
);

$app = new Symfony\Component\Console\Application();
$app->addCommands($container['command.resolver']->commands());

return $app;
