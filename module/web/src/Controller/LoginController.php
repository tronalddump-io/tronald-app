<?php

/**
 * LoginController.php - created 3 Dec 2016 14:10:35
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Controller;

use Auth0\SDK\API;
use Silex;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;

/**
 *
 * LoginController
 *
 * @package Tronald\App\Web
 *
 */
class LoginController
{
    /**
     *
     * @param  Silex\Application $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\InvalidArgumentException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        /** @var Routing\Generator\UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];
        $redirectUrl  = $urlGenerator->generate('web.login', [], Routing\Generator\UrlGenerator::ABSOLUTE_URL);

        $config = $app['config']['auth0'];
        if (empty($config)) {
            throw new Exception\InvalidArgumentException('Config must a non empty value.');
        }

        /** @var API\Authentication $auth0 */
        $auth0       = $app['service']['auth0']['authentication_client'];
        $oAuthClient = $auth0->get_oauth_client(
            $config['client_secret'],
            $redirectUrl,
            [ 'store' => false ] // Use empty store
        );

        if (!$data = $oAuthClient->getUser()) {
            $authorizeUrl = $auth0->get_authorize_link('code', $redirectUrl);

            return $app->redirect($authorizeUrl);
        }

        /** @var \Auth0\SDK\API\Management $client */
        $client = $app['service']['auth0']['management_client'];
        $user = $client->users->get($data['user_id']);

        /** @var Entity\Factory $entityFactory */
        $entityFactory = $app['service']['entity_factory'];
        $user = $entityFactory->fromArray(Entity\User::class, [
            'email'    => $data['email'],
            'user_id'  => $data['user_id'],
            'username' => $data['nickname'],
            'verified' => $data['email_verified']
        ]);

        $app['session']->set('user', $user);

        return $app->redirect(
            $urlGenerator->generate('web.index')
        );
    }
}
