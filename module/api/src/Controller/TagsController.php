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
 * TagsController
 *
 * @package Tronald\Api
 */
class TagsController
{
    /**
     *
     * @param  array                          $response
     * @param  $string                        $tag
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
        $tag,
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
            $urlGenerator->generate('api.get_tag', [
                'tag'  => $tag,
                'page' => 1 < $page ? $page : null
            ]),
            $resourceData
        );

        $resource->addHalLink('prev', new HalLink(
            $urlGenerator->generate('api.get_tag', [
                'tag'  => $tag,
                'page' => $page > 2
                    ? ($page - 1 <= $pages ? $page - 1 : $pages)
                    : null
            ]),
            []
        ));

        $next = $page < $pages ? $page + 1 : null;
        if ($next) {
            $resource->addHalLink('next', new HalLink(
                $urlGenerator->generate('api.get_tag', [ 'tag' => $tag, 'page'  => $next ]),
                []
            ));
        }

        $resource->addHalLink('first', new HalLink(
            $urlGenerator->generate('api.get_tag', [ 'tag' => $tag ]),
            []
        ));

        $resource->addHalLink('last', new HalLink(
            $urlGenerator->generate('api.get_tag', [
                'tag'  => $tag,
                'page' => 0 < $pages ? $pages : null
            ]),
            []
        ));

        foreach ($response['result'] as $quote) {
            if (! ($quote instanceof Entity\Quote)) {
                continue;
            }

            $resource->addResource('tags', $quoteFormatter->toHal(
                $entityFactory->toArray($quote)
            ));
        }

        return $resource;
    }

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

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @param  string                 $tag
     *
     * @throws Exception\InvalidArgumentException
     * @return Http\Json
     */
    public function getTagAction(Silex\Application $app, HttpFoundation\Request $request, $tag)
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

        if (! $tag) {
            throw new Exception\InvalidArgumentException('Parameter "tag" must be a non-empty string.');
        }

        /** @var array $response */
        $response = $app['broker']['quote']->findByTag($tag, $size, $offset);

        return new Http\HalJsonResponse(
            $this->createHalResource(
                $response,
                $tag,
                $size,
                $page,
                $app['url_generator'],
                $app['entity_factory'],
                $app['hal_formatter']['quote']
            )
        );
    }
}
