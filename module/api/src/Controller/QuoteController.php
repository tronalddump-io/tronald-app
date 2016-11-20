<?php

/**
 * QuoteController.php - created 20 Nov 2016 10:54:52
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
use Tronald\Lib\Entity;
use Tronald\Lib\Http;

/**
 *
 * QuoteController
 *
 * @package Tronald\Api\Web
 */
class QuoteController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @param  string                 $id
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request, $id)
    {
        /** @var Entity\Quote $quote */
        $quote = $app['broker']['quote']->get($id);

        return new Http\HalJsonResponse(
            new Hal(
                $app['url_generator']->generate('api.get_quote', [ 'id' => $quote->getId() ]),
                $app['entity_factory']->toArray($quote)
            )
        );
    }
}
