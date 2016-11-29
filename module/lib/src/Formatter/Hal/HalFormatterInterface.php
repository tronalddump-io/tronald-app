<?php

/**
 * HalFormatterInterface.php - created 29 Nov 2016 07:17:21
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
 * HalFormatterInterface
 *
 * @package    Tronald\Lib
 * @subpackage Formatter
 */
interface HalFormatterInterface
{

    /**
     *
     * @param  array $data
     * @return Hal
     */
    public function toHal($data);
}
