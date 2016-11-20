<?php

/**
 * SlackController.php - created 20 Nov 2016 20:14:12
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Api\Controller;

use Silex;
use Symfony\Component\HttpFoundation;
use Tronald\Lib\Http;
use Tronald\Lib\Slack;

/**
 *
 * SlackController
 *
 * @package Tronald\Api\Web
 */
class SlackController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    public function postAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        $command = $app['slack']['command_parser']->createCommandString(
            $request->get('text', null)
        );

        $app['logger']->info(json_encode([
            'type'      => 'slack_command',
            'reference' => $request->headers->get('HTTP_X_REQUEST_ID'),
            'meta'      => [
                'request'  => [
                    'token'        => $request->get('token'),
                    'team_id'      => $request->get('team_id'),
                    'team_domain'  => $request->get('team_domain'),
                    'channel_id'   => $request->get('channel_id'),
                    'channel_name' => $request->get('channel_name'),
                    'user_id'      => $request->get('user_id'),
                    'user_name'    => $request->get('user_name'),
                    'command'      => $request->get('command'),
                    'text'         => $request->get('text'),
                    'response_url' => $request->get('response_url')
                ]
            ]
        ]));

        return new Http\JsonResponse(
            $command->toArray()
        );
    }
}