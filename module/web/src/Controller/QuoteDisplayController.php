<?php

/**
 * QuoteDisplayController.php - created 13 Nov 2016 08:56:35
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
use Tronald\Lib\Entity;

/**
 *
 * QuoteDisplayController
 *
 * @package Tronald\App\Web
 */
class QuoteDisplayController
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
        return $app['twig']->render('quote/display.twig.html', [
            'quote' => Entity\Factory::toArray(
                $app['broker']['quote']->get($id)
            )
        ]);
    }
}