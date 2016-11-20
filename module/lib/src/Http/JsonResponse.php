<?php

/**
 * JsonResponse.php - created 20 Nov 2016 10:39:16
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Http;

use Symfony\Component\HttpFoundation;

/**
 *
 * JsonResponse
 *
 * @package Tronald\Lib
 */
class JsonResponse extends HttpFoundation\JsonResponse
{

    /**
     * @param mixed   $data    The response data
     * @param integer $status  The response status code
     * @param array   $headers An array of response headers
     * @param boolean $json    If the data is already a JSON string
     */
    public function __construct($data = null, $status = 200, $headers = [], $json = false)
    {
        parent::__construct($data, $status, $headers, $json);
    }
}