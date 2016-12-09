<?php

/**
  * RandomQuoteController.php - created 7 Dec 2016 22:32:05
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
use Tronald\Lib\Broker;
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;
use Tronald\Lib\Http;

/**
 *
 * RandomQuoteController
 *
 * @package Tronald\Api
 */
class RandomQuoteController
{
    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @throws Exception\InvalidArgumentException
     * @return Http\HalJsonResponse
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        $tag = $request->get('tag', null);
        if ('' === $tag) {
            throw new Exception\InvalidArgumentException('Parameter "tag" must be a non empty string.');
        }

        /** @var Entity\Quote $quote */
        $quote = $app['broker']['quote']->random($tag);
        $data  = $app['entity_factory']->toArray($quote);

        return new Http\HalJsonResponse(
            $app['hal_formatter']['quote']->toHal($data)
        );
    }
}
