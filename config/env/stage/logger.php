<?php
use Bramus\Monolog\Formatter;

$streamHandler = new Monolog\Handler\StreamHandler('php://stdout', Monolog\Logger::DEBUG);
$streamHandler->setFormatter(
    new Formatter\ColoredLineFormatter(new Formatter\ColorSchemes\TrafficLight)
);

return [
    'logger' => [
        'monolog.name'    => 'tronald_dump_api',
        'monolog.handler' => $streamHandler
    ]
];
