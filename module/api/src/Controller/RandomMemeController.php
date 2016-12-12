<?php

/**
 * RandomMemeController.php - created 11 Dec 2016 20:59:14
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
use Tronald\Lib\Entity;
use Tronald\Lib\Exception;
use Tronald\Lib\MemeGenerator;

/**
 *
 * RandomMemeController
 *
 * @package Tronald\Api\Api
 */
class RandomMemeController
{

    /**
     *
     * @param  Silex\Application      $app
     * @param  HttpFoundation\Request $request
     * @param  integer                $retries
     * @throws Exception\InvalidArgumentException
     * @throws Exception\RuntimeException
     * @return HttpFoundation\Response
     */
    public function getAction(Silex\Application $app, HttpFoundation\Request $request, $retries = 2)
    {
        $size = $request->get('size', 1);
        if ($size < 0.5 || 5 < $size) {
            throw new Exception\InvalidArgumentException('Argument "size" must be between 0.5 and 5');
        }

        $memeGenerator = MemeGenerator\MemeGeneratorFactory::create();

        while ($retries >= 0) {
            if (0 === $retries) {
                throw new Exception\RuntimeException('Maximum number of retries reached.');
            }

            /** @var Entity\Quote $quote */
            $quote = $app['broker']['quote']->random();

            // remove urls
            $text = preg_replace(
                '/\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|$!:,.;]*[A-Z0-9+&@#\/%=~_|$]/i',
                '',
                $quote->getValue()
            );

            try {
                $meme = $memeGenerator
                    ->setText($text)
                    ->setSize($size)
                    ->generate();
                break;
            } catch (Exception\TokenLengthException $exception) {
            }

            $retries--;
        }

        return new HttpFoundation\Response($meme, 200, [
            'content-type'        => $meme->getMimeType(),
            'content-disposition' => sprintf(
                'filename="%s.%s"',
                $quote->getId(),
                substr($meme->getMimeType(), 6)
            ),
            'tronald-quote-id'    => $quote->getId()
        ]);
    }
}
