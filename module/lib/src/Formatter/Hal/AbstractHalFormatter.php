<?php

/**
 * AbstractHalFormatter.php - created 29 Nov 2016 07:25:11
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Formatter\Hal;

use Symfony\Component\Routing;

/**
 *
 * AbstractHalFormatter
 *
 * @package    Tronald\Lib
 * @subpackage Formatter
 */
abstract class AbstractHalFormatter
{

    /**
     *
     * @var Routing\Generator\UrlGenerator
     */
    protected $urlGenerator;

    /**
     *
     * @param Routing\Generator\UrlGenerator $urlGenerator
     */
    public function __construct(Routing\Generator\UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }
}
