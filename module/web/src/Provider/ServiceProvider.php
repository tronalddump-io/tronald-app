<?php

/**
 * ServiceProvider.php - created 3 Dec 2016 14:13:21
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;

/**
 *
 * ServiceProvider
 *
 * @package Tronal\App\Web
 *
 */
class ServiceProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['service'] = function () use ($app) {
            $clientId = getenv('AUTH0_CLIENT_ID') ?: null;
            if (! $clientId) {
                throw new Exception\InvalidArgumentException('Client id must be an non-empty valid.');
            }

            $domain = getenv('AUTH0_DOMAIN') ?: null;
            if (! $domain) {
                throw new Exception\InvalidArgumentException('Api domain must be an non-empty valid.');
            }

            $token = getenv('AUTH0_API_TOKEN') ?: null;
            if (! $token) {
                throw new Exception\InvalidArgumentException('Api token must be an non-empty valid.');
            }

            return [
                'auth0' => [
                    'authentication_client' => new \Auth0\SDK\API\Authentication($domain, $clientId),
                    'management_client'     => new \Auth0\SDK\API\Management($token, $domain)
                ],
                'entity_factory' => new Entity\Factory()
            ];
        };
    }
}