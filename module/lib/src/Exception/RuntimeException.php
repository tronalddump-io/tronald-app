<?php

/**
 * RuntimeException.php - created 11 Dec 2016 16:40:27
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Exception;

/**
 *
 * RuntimeException
 *
 * @package Tronald\Lib
 *
 */
class RuntimeException extends \RuntimeException
{

    /**
     *
     * @param string  $message
     * @param integer $code
     * @param \Exception $previous
     */
    public function __construct($message = null, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
