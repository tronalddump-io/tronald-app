<?php

/**
 * MemeText.php - created 11 Dec 2016 21:21:36
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\MemeGenerator;

use Tronald\Lib\Exception;

/**
 *
 * MemeText
 *
 * @package Tronald\Lib\MemeGenerator
 */
class MemeText
{

    /**
     *
     * @var array
     */
    private $lines = [];

    /**
     *
     * @param string  $value         A text string
     * @param integer $maxLineLength Maximum number of characters per line
     */
    public function __construct($value, $maxLineLength = 50)
    {
        $this->lines = $this->splitStringIntoLines($value, $maxLineLength);
    }

    /**
     *
     * @param  string  $string
     * @param  integer $maxLineLength
     * @param  array   $lines
     * @throws Exception\TokenLengthException
     * @return array
     */
    private function splitStringIntoLines($string, $maxLineLength, $lines = [])
    {
        if (strlen($string) <= 0) {
            return $lines;
        }

        $line = '';
        foreach (explode(' ', $string) as $key => $token) {
            if ($maxLineLength < strlen($token)) {
                throw new Exception\TokenLengthException();
            }

            $appendix = $key === 0
                ? sprintf('%s', $token)
                : sprintf(' %s', $token);

            if (strlen($line) + strlen($appendix) > $maxLineLength) {
                break;
            }

            $line .= $appendix;
        }

        array_push($lines, $line);

        $string = substr(
            $string,
            strlen($line)
        );

        return $this->splitStringIntoLines(
            trim($string),
            $maxLineLength,
            $lines
        );
    }

    /**
     *
     * @return string[]
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     *
     * @return integer
     */
    public function getLineCount()
    {
        return count($this->lines);
    }
}
