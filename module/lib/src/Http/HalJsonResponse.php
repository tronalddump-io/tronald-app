<?php

/**
 * HalJsonResponse.php - created 20 Nov 2016 10:39:16
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Http;

use Nocarrier\Hal;
use Symfony\Component\HttpFoundation;

/**
 *
 * HalJsonResponse
 *
 * @package Tronald\Lib
 */
class HalJsonResponse extends HttpFoundation\JsonResponse
{

    /**
     *
     * @param Hal     $resource The hal resource
     * @param integer $status   The response status code
     * @param array   $headers  An array of response headers
     */
    public function __construct(Hal $resource = null, $status = 200, $headers = [])
    {
        $headers = array_change_key_case($headers, CASE_LOWER);
        $headers = array_merge($headers, [
            'content-type' => 'application/hal+json'
        ]);

        parent::__construct($resource->asJson(), $status, array_unique($headers), true);
    }
}