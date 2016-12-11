<?php

/**
 * SearchQuoteController.php - created 20 Nov 2016 11:40:58
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Api\Controller;

use Nocarrier\Hal;
use Nocarrier\HalLink;
use Silex;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Tronald\Lib\Broker;
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;
use Tronald\Lib\Formatter;
use Tronald\Lib\Http;

/**
 *
 * SearchQuoteController
 *
 * @package Tronald\Api\Web
 */
class SearchQuoteController
{
    /**
     *
     * @param  array                          $response
     * @param  $string                        $query
     * @param  $integer                       $limit
     * @param  $integer                       $page
     * @param  Routing\Generator\UrlGenerator $urlGenerator
     * @param  Entity\Factory                 $entityFactory
     * @param  Formatter\Hal\QuoteFormatter   $quoteFormatter
     *
     * @return Hal
     */
    private function createHalResource(
        array $response,
        $query,
        $limit,
        $page,
        Routing\Generator\UrlGenerator $urlGenerator,
        Entity\Factory $entityFactory,
        Formatter\Hal\QuoteFormatter $quoteFormatter
    ) {
        $resourceData = [
            'count'     => count($response['result']),
            'total'     => $response['total'],
            '_embedded' => []
        ];

        $pages = (int) ceil($response['total'] / $limit);

        $resource = new Hal(
            $urlGenerator->generate('api.search_quote', [
                'query' => $query,
                'page'  => 1 < $page ? $page : null
            ]),
            $resourceData
        );

        $resource->addHalLink('prev', new HalLink(
            $urlGenerator->generate('api.search_quote', [
                'query' => $query,
                'page'  => $page > 2
                    ? ($page - 1 <= $pages ? $page - 1 : $pages)
                    : null
            ]),
            []
        ));

        $next = $page < $pages ? $page + 1 : null;
        if ($next) {
            $resource->addHalLink('next', new HalLink(
                $urlGenerator->generate('api.search_quote', [ 'query' => $query, 'page'  => $next ]),
                []
            ));
        }

        $resource->addHalLink('first', new HalLink(
            $urlGenerator->generate('api.search_quote', [ 'query' => $query ]),
            []
        ));

        $resource->addHalLink('last', new HalLink(
            $urlGenerator->generate('api.search_quote', [
                'query' => $query,
                'page'  => 0 < $pages ? $pages : null
            ]),
            []
        ));

        foreach ($response['result'] as $quote) {
            if (! ($quote instanceof Entity\Quote)) {
                continue;
            }

            $resource->addResource('quotes', $quoteFormatter->toHal(
                $entityFactory->toArray($quote)
            ));
        }

        return $resource;
    }

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     *
     * @throws Exception\InvalidArgumentException
     * @return Http\Json
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        $max  = $app['config']['pagination']['max_items'];
        $min  = $app['config']['pagination']['min_items'];
        $size = (int) $request->query->get('size', $max);
        if ($size > $max || $size < $min) {
            throw new Exception\InvalidArgumentException(
                sprintf('Parameter "size" must be between %s - %s.', $min, $max)
            );
        }

        $query  = $request->query->get('query');
        $page   = (int) $request->query->get('page', 1);
        $offset = $page > 1 ? ($page - 1) * $size : 0;

        if (! $query) {
            throw new Exception\InvalidArgumentException('Parameter "query" must be a non-empty value');
        }

        if (3 > strlen($query)) {
            throw new Exception\InvalidArgumentException('Parameter "query" must have minimum length of 3 characters');
        }

        /** @var array $response */
        $response = $app['broker']['quote']->search($query, $size, $offset);

        return new Http\HalJsonResponse(
            $this->createHalResource(
                $response,
                $query,
                $size,
                $page,
                $app['url_generator'],
                $app['entity_factory'],
                $app['hal_formatter']['quote']
            )
        );
    }
}
