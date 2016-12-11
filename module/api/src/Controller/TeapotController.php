<?php

/**
  * TeapotController.php - created 11 Dec 2016 13:04:10
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

/**
 *
 * TeapotController
 *
 * @package Tronald\Api
 */
class TeapotController
{
    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request)
    {
        $filename = sprintf('%s/resource/teapot.txt', MODULE_PATH);
        $contents = file_get_contents($filename);

        return new HttpFoundation\Response($contents, 418, [
            'content-type' => 'text/plain'
        ]);
    }
}
