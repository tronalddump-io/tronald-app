<?php

/**
 * HelpCommand.php - created 20 Nov 2016 20:29:00
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
 * HelpCommand
 *
 * @package Tronald\Lib
 *
 */
class HelpCommand extends AbstractCommand
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
        $attachments = [
            0 => [
                'title'     => 'Get random Tronald trash',
                'text'      => 'Type `/tronald` to get a random quote.',
                'mrkdwn_in' => [ 'text' ]
            ],
            1 => [
                'title'     => 'Free text search',
                'text'      => 'Type `/tronald ? {search_term}` to search The Tronald\'s dump.',
                'mrkdwn_in' => [ 'text' ]
            ],
            2 => [
                'title'     => 'Help',
                'text'      => 'Type `/tronald help` to display this help context',
                'mrkdwn_in' => [ 'text' ]
            ]
        ];

        return [
            'attachments'   => $attachments,
            'icon_url'      => parent::ICON_AVATAR_URL,
            'mrkdwn'        => true,
            'response_type' => parent::RESPONSE_TYPE_EPHEMERAL,
            'text'          => '*Available commands:*'
        ];
    }
}
