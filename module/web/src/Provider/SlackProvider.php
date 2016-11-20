<?php

/**
 * SlackProvider.php - created 20 Nov 2016 18:32:10
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
use Symfony\Component\Routing;

/**
 *
 * SlackProvider
 *
 * @package Tronal\App\Web
 *
 */
class SlackProvider implements ServiceProviderInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Pimple\ServiceProviderInterface::register()
     */
    public function register(Container $app)
    {
        $app['slack'] = function () use ($app) {
            $auth = getenv('SLACK_AUTH') ? json_decode(getenv('SLACK_AUTH')) : null;
            $data = [
                'client_id'    => $auth->clientId,
                'redirect_uri' => $app['url_generator']->generate(
                    'web.connect_slack',
                    [],
                    Routing\Generator\UrlGenerator::ABSOLUTE_URL
                ),
                'scope'        => 'commands'
            ];

            return [
                'auth'               => [
                    'client_id'     => $auth->clientId,
                    'client_secret' => $auth->clientSecret
                ],
                'connect_url'        => sprintf('https://slack.com/oauth/authorize?%s', http_build_query($data)),
                'redirect_url'       => $data['redirect_uri'],
                'verification_token' => getenv('SLACK_VERIFICATION_TOKEN') ?: null
            ];
        };
    }
}
