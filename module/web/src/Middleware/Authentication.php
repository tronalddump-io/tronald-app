<?php

/**
 * Authentication.php - created 3 Dec 2016 16:37:44
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Middleware;

use Silex\Application;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Tronald\Lib\Entity;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 *
 * Authentication
 *
 * @package Tronald\Web\Authentication
 *
 */
class Authentication
{
    /**
     *
     * @param  HttpFoundation\Request $request
     * @param  Application            $app
     */
    public static function authenticate(HttpFoundation\Request $request, Application $app)
    {
        if (! ($app['session']->get('user') instanceof Entity\User)) {
            /** @var Routing\Generator\UrlGenerator $urlGenerator */
            $urlGenerator = $app['url_generator'];
            $url = $urlGenerator->generate('web.login', [], Routing\Generator\UrlGenerator::ABSOLUTE_URL);

            return $app->redirect($url);
        }
    }

    /**
     *
     * @param  HttpFoundation\Request $request
     * @param  Application            $app
     */
    public static function unauthenticate(HttpFoundation\Request $request, Application $app)
    {
        if ($app['session']->get('user') instanceof Entity\User) {
            $app['session']->set('user', null);
        }
    }
}