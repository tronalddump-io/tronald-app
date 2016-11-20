<?php

/**
 * Verification.php - created 20 Nov 2016 20:17:33
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Api\Middleware;

use Silex\Application;
use Symfony\Component\HttpFoundation;
use Tronald\Lib\Exception;

/**
 *
 * Verification
 *
 * @package Tronald\App\Api
 *
 */
class Verification
{
    /**
     * Verify the slack origin
     *
     * @param  HttpFoundation\Request $request
     * @param  Application            $app
     * @throws Exception\SlackVerificationTokenException
     */
    public static function slackOrigin(HttpFoundation\Request $request, Application $app)
    {
        $token = $request->request->get(
            'token',
            $request->query->get('token', null)
        );

        if ($token !== $app['slack']['verification_token']) {
            throw new Exception\SlackVerificationTokenException();
        }
    }
}
