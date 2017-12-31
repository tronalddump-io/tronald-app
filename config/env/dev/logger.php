<?php
use Bramus\Monolog\Formatter;
use Tronald\Lib\Util;

$streamHandler = new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::DEBUG);
$streamHandler->setFormatter(
    new Formatter\ColoredLineFormatter(new Formatter\ColorSchemes\TrafficLight)
);

return [
    'logger' => [
        'monolog.name'    => Util::getEnvOrDefault('APPLICATION_NAME', 'tronald_dump_undefined'),
        'monolog.handler' => $streamHandler
    ]
];
