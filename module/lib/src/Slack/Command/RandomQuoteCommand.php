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

use Tronald\Lib\Entity;

/**
 *
 * RandomQuoteCommand
 *
 * @package Tronald\Lib
 *
 */
class RandomQuoteCommand extends AbstractCommand
{

    /**
     *
     * @var string
     */
    protected $input;

    /**
     *
     * @var Entity\Quote
     */
    protected $quote;

    /**
     *
     * @param string       $input
     * @param Entity\Quote $quote
     */
    public function __construct($input, Entity\Quote $quote)
    {
        $this->input = $input;
        $this->text  = $quote->getValue();
        $this->quote = $quote;
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
     * @see \Tronald\Lib\Slack\Command\AbstractCommand::getText()
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tronald\Lib\Slack\Command\AbstractCommand::toArray()
     */
    public function toArray()
    {
        return [
            'attachments'   => [
                [
                    'fallback'   => $this->getText(),
                    'title'      => '[permalink]',
                    'title_link' => sprintf(
                        '%s/quote/%s?utm_source=slack&utm_medium=api&utm_term=%s&utm_campaign=random+quote',
                        parent::WEB_BASE_URL,
                        $this->quote->getId(),
                        $this->quote->getId()
                    ),
                    'text'       => $this->getText(),
                    'mrkdwn_in'  => [ 'text' ]
                ]
            ],
            'icon_url'      => parent::ICON_AVATAR_URL,
            'response_type' => parent::RESPONSE_TYPE_IN_CHANNEL
        ];
    }
}
