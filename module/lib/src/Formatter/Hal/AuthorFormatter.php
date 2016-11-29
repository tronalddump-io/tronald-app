<?php

/**
 * AuthorFormatter.php - created 29 Nov 2016 07:15:45
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Formatter\Hal;

use Nocarrier\Hal;

/**
 *
 * AuthorFormatter
 *
 * @package    Tronald\Lib
 * @subpackage Formatter
 */
class AuthorFormatter extends AbstractHalFormatter implements HalFormatterInterface
{
    /**
     *
     * {@inheritDoc}
     * @see \Tronald\Lib\Formatter\Hal\HalFormatterInterface::toHal()
     */
    public function toHal($data)
    {
        return new Hal(
            null,
            $data
        );
    }
}
