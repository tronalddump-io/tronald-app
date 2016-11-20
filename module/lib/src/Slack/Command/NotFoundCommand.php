<?php

/**
 * RandomQuoteCommand.php - created 20 Nov 2016 20:29:00
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Slack\Command;

/**
 *
 * NotFoundCommand
 *
 * @package Tronald\Lib
 *
 */
class NotFoundCommand extends AbstractCommand
{

    /**
     *
     * @var string
     */
    protected $input;

    /**
     *
     * @param string $input
     */
    public function __construct($input)
    {
        $this->input = $input;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tronald\Lib\Slack\Command\AbstractCommand::getInput()
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tronald\Lib\Slack\Command\AbstractCommand::toArray()
     */
    public function toArray()
    {
        return [
            'icon_url'      => parent::ICON_AVATAR_URL,
            'text'          => sprintf('Mhh, I did not quite understand your command `%s`.', $this->getInput()),
            'mrkdwn'        => true,
            'response_type' => parent::RESPONSE_TYPE_EPHEMERAL
        ];
    }
}
