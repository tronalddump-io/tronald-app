<?php

/**
 * MemeGenerator.php - created 11 Dec 2016 16:17:41
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\MemeGenerator;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Tronald\Lib\Exception;

/**
 *
 * MemeGenerator
 *
 * @package Tronald\Lib\MemeGenerator
 */
class MemeGenerator
{
    /**
     *
     * @var Imagick
     */
    private $cover;

    /**
     *
     * @var integer
     */
    private $height = self::DEFAULT_HEIGHT;

    /**
     *
     * @var array
     */
    private static $map = [
        self::BG_IMG_000001,
        self::BG_IMG_000002,
        self::BG_IMG_000003,
        self::BG_IMG_000004
    ];

    /**
     *
     * @var integer
     */
    private static $size = 1.5;

    /**
     *
     * @var MemeText
     */
    private $text;

    /**
     *
     * @var integer
     */
    private $width = self::DEFAULT_WIDTH;

    const BG_IMG_000001 = 'bg_img_000001.jpg';
    const BG_IMG_000002 = 'bg_img_000002.jpg';
    const BG_IMG_000003 = 'bg_img_000003.jpg';
    const BG_IMG_000004 = 'bg_img_000004.jpg';

    const DEFAULT_HEIGHT = 768;
    const DEFAULT_WIDTH  = 1024;

    /**
     * @return void
     */
    final public function __construct()
    {
        if (! extension_loaded('imagick')) {
            throw new Exception\RuntimeException('PHP "imagick" extension not installed.');
        }
    }

    /**
     *
     * @return \Tronald\Lib\MemeGenerator\Meme
     */
    public function generate()
    {
        // Set random bg if nothing has been set
        if (! $this->cover) {
            $randomKey = array_rand(self::$map);
            $this->setCover(self::$map[$randomKey]);
        }

        $height = $this->height * self::$size;
        $width  = $this->width  * self::$size;

        // create background drawing
        $veraBold = $this->resolveRealPath('font', 'bitstream-vera-sans/vera-bold.ttf');

        $bachground = new ImagickDraw();
        $bachground->setFont($veraBold);
        $bachground->setFontSize(30 * self::$size);
        $bachground->setFillColor('#ffffff');
        $bachground->settextalignment(Imagick::ALIGN_CENTER);

        $this->cover->borderImage(
            '#ffffff',
            10 * self::$size,
            15 * self::$size
        );

        $this->cover->scaleImage(
            $width * 1,
            $height * 0.725,
            false
        );

        $image = new Imagick();
        $image->newImage(
            $width,
            $height,
            $pixel = new ImagickPixel()
        );
        $image->setImageFormat('jpeg');
        $image->compositeImage(
            $this->cover,
            $this->cover->getImageCompose(),
            0,
            0
        );

        $image->borderImage(
            '#000000',
            20 * self::$size,
            20 * self::$size
        );

        // add bottom text
        $x     = ceil($width / 2) - 10;
        $y     = 610 * self::$size;
        $angle = 0;
        foreach ($this->text->getLines() as $line) {
            $image->annotateImage($bachground, $x, $y += 35 * self::$size, $angle, $line);
        }

        $meme = new Meme($image, $this->text);

        $this->reset();

        return $meme;
    }

    /**
     *
     * @return void
     */
    private function reset()
    {
        $this->cover  = null;
        $this->height = self::DEFAULT_HEIGHT;
        $this->text   = null;
        $this->width  = self::DEFAULT_WIDTH;
    }

    /**
     *
     * @param  string $key
     * @return string
     */
    private function resolveRealPath($type, $key)
    {
        return sprintf('%s/../../resource/meme/%s/%s', __DIR__, $type, $key);
    }

    /**
     *
     * @param  string $imageKey
     * @throws Exception\InvalidArgumentException
     * @return \Tronald\Lib\MemeGenerator\MemeGenerator
     */
    public function setCover($imageKey)
    {
        if (! in_array($imageKey, self::$map)) {
            $message = sprintf('Unsupported image key "%s" given.', $imageKey);
            throw new Exception\InvalidArgumentException($message);
        }

        $path = $this->resolveRealPath('image', $imageKey);
        $this->cover = new Imagick($path);

        return $this;
    }

    /**
     *
     * @param  float $size
     * @return \Tronald\Lib\MemeGenerator\MemeGenerator
     */
    public function setSize($size)
    {
        self::$size = $size;
        return $this;
    }

    /**
     *
     * @param  string $string
     * @return \Tronald\Lib\MemeGenerator\MemeGenerator
     */
    public function setText($text)
    {
        if ($text) {
            $this->text = new MemeText($text, $maxLineLength = 54);
        }

        return $this;
    }
}
