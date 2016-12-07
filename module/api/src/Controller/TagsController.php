<?php

/**
  * TagsController.php - created 7 Dec 2016 22:44:50
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\App\Api\Controller;

use Nocarrier\Hal;
use Silex;
use Symfony\Component\HttpFoundation;
use Tronald\Lib\Broker;
use Tronald\Lib\Http;

/**
 *
 * TagsController
 *
 * @package Tronald\Api
 */
class TagsController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        /** @var array $tags */
        $tags = $app['broker']['quote']->findTags();

        $resource = new Hal(
            $app['url_generator']->generate('api.tags'), [
                'count'     => $length = count($tags),
                'total'     => $length,
                '_embedded' => is_array($tags) ? array_map(function($tag) {
                    return $tag->name;
                }, $tags) : []
            ]
        );

        return new Http\HalJsonResponse($resource);
    }
}
