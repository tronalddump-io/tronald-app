<?php

/**
  * TokenLengthException.php - created 11 Dec 2016 22:27:17
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
 * TokenLengthException
 *
 * @package Tronald\Lib
 *
 */
class TokenLengthException extends \LengthException
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
