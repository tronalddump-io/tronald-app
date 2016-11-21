<?php

/**
 * TagDisplayController.php - created 13 Nov 2016 08:56:35
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\App\Web\Controller;

use Silex;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing;
use Tronald\Lib\Entity;

/**
 *
 * TagDisplayController
 *
 * @package Tronald\App\Web
 */
class TagDisplayController
{
    /**
     *
     * @var integer
     */
    const ITEMS_PER_PAGE = 10;

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @param  string                 $tag
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request, $tag)
    {
        $page   = (int) $request->query->get('page', 1);
        $offset = $page > 1 ? ($page - 1) * self::ITEMS_PER_PAGE : 0;
        $res    = $app['broker']['quote']->findByTag($tag, self::ITEMS_PER_PAGE, $offset);
        $pages = (int) ceil($res['total'] / self::ITEMS_PER_PAGE);

        /** @var Routing\Generator\UrlGenerator $urlGenerator */
        $urlGenerator = $app['url_generator'];

        return $app['twig']->render('tag/display.twig.html', [
            'tag'    => $tag,
            'total'  => $res['total'],
            'quotes' => is_array($res['result']) ? array_map(function($quote) {
                return Entity\Factory::toArray($quote);
            }, $res['result']) : [],
            'pagination' => [
                'current' => $urlGenerator->generate(
                    'web.get_tag',
                    ['tag' => $tag, 'page' => $page > 1 ? $page : null]
                ),
                'next'    => $page < $pages
                    ? $urlGenerator->generate('web.get_tag', ['tag' => $tag, 'page' => $page + 1])
                    : null,
                'prev'    => $page > 1
                    ? $urlGenerator->generate('web.get_tag', ['tag' => $tag, 'page' => $page - 1])
                    : null
            ]
        ]);
    }
}