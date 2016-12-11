<?php
$streamHandler = new Monolog\Handler\StreamHandler(
    sprintf('%s/log/%s.log', APPLICATION_PATH, APPLICATION_NAME),
    Monolog\Logger::DEBUG
);

return [
    'logger' => [
        'monolog.name'    => APPLICATION_NAME,
        'monolog.handler' => $streamHandler
    ]
];
