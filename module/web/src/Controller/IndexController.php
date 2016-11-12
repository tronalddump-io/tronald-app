<?php

/**
 * IndexController.php - created Mar 6, 2016 3:03:18 PM
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

/**
 *
 * IndexController
 *
 * @package Tronald\App\Web
 */
class IndexController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @return string
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        return $app['twig']->render(
            'index.twig.html',
            [
                'tags' => $app['broker']['quote']->findTags()
            ]
        );
    }
}
