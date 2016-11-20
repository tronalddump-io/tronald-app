<?php

/**
 * ConnectController.php - created 20 Nov 2016 18:06:10
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Controller\Slack;

use Exception;
use Silex;
use Symfony\Component\HttpFoundation;

/**
 *
 * ConnectController
 *
 * @package Tronald\Api\Web
 */
class ConnectController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        if ($code = $request->get('code', null)) {
            return $this->connectAction($code, $app, $request);
        }

        return $app['twig']->render(
            'slack/connect.twig.html',
            [
                'connect_url' => $app['slack']['connect_url']
            ]
        );
    }

    /**
     *
     * @param  string $code
     * @param  Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    private function connectAction($code, Silex\Application $app, HttpFoundation\Request $request)
    {
        $provider = new \Bramdevries\Oauth\Client\Provider\Slack([
            'clientId'     => $app['slack']['auth']['client_id'],
            'clientSecret' => $app['slack']['auth']['client_secret'],
            'redirectUri'  => $app['slack']['redirect_url']
        ]);

        try {
            $response = $provider->getResourceOwner(
                $token = $provider->getAccessToken('authorization_code', [ 'code' => $code ])
            );
        } catch (Exception $exception) {
            $app['logger']->critical($exception);

            return $app['twig']->render(
                'slack/error.twig.html',
                [
                    'connect_url' => $app['slack']['connect_url'],
                    'error'       => 'An error occurred while adding The Tronald to your Slack console. Please try again.'
                ]
            );
        }

        $resourceOwner = $response instanceof \Bramdevries\Oauth\Client\Provider\ResourceOwner
            ? $response->toArray()
            : [];

        $app['logger']->info(json_encode([
            'type'      => 'slack_connect',
            'reference' => $request->headers->get('HTTP_X_REQUEST_ID'),
            'meta'      => [
                'resource_owner'  => [
                    'team_domain' => $resourceOwner['team'],
                    'team_id'     => $resourceOwner['team_id'],
                    'user_id'     => $resourceOwner['user_id'],
                    'user_name'   => $resourceOwner['user']
                ]
            ]
        ]));

        return $app['twig']->render(
            'slack/success.twig.html',
            [
                'team_name' => $resourceOwner['team']
            ]
        );
    }
}
