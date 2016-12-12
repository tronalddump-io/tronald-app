<?php

/**
 * MemeGeneratorFactory.php - created 11 Dec 2016 16:18:23
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\MemeGenerator;

/**
 *
 * MemeGeneratorFactory
 *
 * @package Tronald\Lib\MemeGenerator
 */
class MemeGeneratorFactory
{

    /**
     *
     * @return \Tronald\Lib\MemeGenerator\MemeGenerator
     */
    public static function create()
    {
        return new MemeGenerator();
    }
}
