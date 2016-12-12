<?php

/**
 * Meme.php - created 11 Dec 2016 23:01:32
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\MemeGenerator;

use Imagick;

/**
 *
 * Meme
 *
 * @package Tronald\Lib\MemeGenerator
 */
class Meme
{

    /**
     *
     * @var Imagick
     */
    private $image;

    /**
     *
     * @var MemeText
     */
    private $text;

    /**
     *
     * @param Imagick $image
     * @param MemeText $text
     * @return void
     */
    public function __construct(Imagick $image, MemeText $text)
    {
        $this->image = $image;
        $this->text  = $text;
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->image->getImageBlob();
    }

    /**
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->image->getImageMimeType();
    }

    /**
     *
     * @return \Tronald\Lib\MemeGenerator\MemeText
     */
    public function getText()
    {
        return $this->text;
    }
}
