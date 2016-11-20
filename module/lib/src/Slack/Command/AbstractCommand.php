<?php

/**
 * AbstractCommand.php - created 20 Nov 2016 20:29:00
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Slack\Command;

use Tronald\Lib\Slack;

/**
 *
 * AbstractCommand
 *
 * @package Tronald\Lib
 *
 */
abstract class AbstractCommand
{

    /**
     * The holy shrug
     *
     * @var string
     */
    const ICON_ASCII_SHRUG = '¯\_(ツ)_/¯';

    /**
     *
     * @var string
     */
    const ICON_AVATAR_URL = 'https://assets.tronalddump.io/img/tronalddump_150x150.png';

    /**
     *
     * @var string
     */
    const RESPONSE_TYPE_EPHEMERAL = 'ephemeral';

    /**
     *
     * @var string
     */
    const RESPONSE_TYPE_IN_CHANNEL = 'in_channel';

    /**
     *
     * @var string
     */
    const WEB_BASE_URL = 'https://www.tronalddump.io';

    /**
     *
     * @return string
     */
    abstract public function getInput();

    /**
     *
     * @return array
     */
    abstract public function toArray();
}
