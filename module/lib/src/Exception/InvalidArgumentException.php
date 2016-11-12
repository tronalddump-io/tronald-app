<?php

/**
 * InvalidArgumentException.php - created 13 Nov 2016 13:10:40
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Exception;

use \Symfony\Component\HttpKernel\Exception as Exception;

/**
 *
 * InvalidArgumentException
 *
 * @package Tronald\Lib
 *
 */
class InvalidArgumentException extends Exception\PreconditionFailedHttpException
{

    /**
     *
     * @param string     $message
     * @param \Exception $previous
     * @param integer    $code
     */
    public function __construct($message, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $previous, $code);
    }
}
