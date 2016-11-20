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

if (! defined('APPLICATION_PATH')) {
    define('APPLICATION_PATH', realpath(__DIR__ . '/../'));
}

if (! defined('CONFIG_PATH')) {
    define('CONFIG_PATH', realpath(APPLICATION_PATH . '/config/'));
}

// Setup autoloading
require_once sprintf('%s/vendor/autoload.php', APPLICATION_PATH);

// Load application config
$config = include sprintf('%s/config/application.config.php', APPLICATION_PATH);

// Load env vars from dot env file
if (file_exists(sprintf('%s/.env', APPLICATION_PATH))) {
    $dotenv = new \Dotenv\Dotenv(APPLICATION_PATH);
    $dotenv->load();
}

// Load module
if ('cli' === php_sapi_name()) {
    $module = 'cli';
} else if (array_key_exists($_SERVER['SERVER_NAME'], $config['server_names'])) {
    $module = $config['server_names'][$_SERVER['SERVER_NAME']];
} else {
    throw new \RuntimeException(
        sprintf('Could not determine module from server name "%s".', $_SERVER['SERVER_NAME'])
    );
}

if (! defined('MODULE_PATH')) {
    define('MODULE_PATH', realpath(APPLICATION_PATH . '/module/'. $module));
}

include sprintf('%s/%s', APPLICATION_PATH, $config['modules'][$module]);

$app->run();
