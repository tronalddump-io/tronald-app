<?php

/**
 * SearchQuoteCommand.php - created 20 Nov 2016 20:29:00
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
 * SearchQuoteCommand
 *
 * @package Tronald\Lib
 *
 */
class SearchQuoteCommand extends AbstractCommand
{

    /**
     *
     * @var string
     */
    protected $input;

    /**
     *
     * @var integer
     */
    protected $maxItems;

    /**
     *
     * @var integer
     */
    protected $offset;

    /**
     *
     * @var integer
     */
    protected $page;

    /**
     *
     * @var string
     */
    protected $query;

    /**
     *
     * @var Entity\Quote[]
     */
    protected $results;

    /**
     *
     * @var integer
     */
    protected $total;

    /**
     *
     * @param string $input
     * @param array  $response
     */
    public function __construct($input, $response)
    {
        $this->input    = $input;
        $this->maxItems = $response['max_items'];
        $this->offset   = $response['offset'];
        $this->page     = $response['page'];
        $this->query    = $response['query'];
        $this->results  = $response['results'];
        $this->total    = $response['total'];
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
        if (0 === $this->total) {
            return [
                'icon_url'      => parent::ICON_AVATAR_URL,
                'text'          => sprintf(
                    'Your search for *"%s"* did not match any quote. Make sure that all words are spelled correctly. Try different keywords. Try more general keywords.',
                    $this->query
                ),
                'mrkdwn'        => true,
                'response_type' => parent::RESPONSE_TYPE_EPHEMERAL
            ];
        }

        $attachments = [];
        foreach ($this->results as $index => $quote) {
            $attachment = [
                'title'     => sprintf('(%d)', $this->offset + $index + 1),
                'text'      => $quote->getValue() . "\n",
                'mrkdwn_in' => [ 'text' ]
            ];

            array_push($attachments, $attachment);
        }

        $text = sprintf(
            '*Search results: %s - %s of %s*.',
            0 === $this->offset ? 1 : $this->offset + 1,
            $shown = $this->offset + $index + 1,
            number_format($this->total, 0, '.', ','),
            $this->query
        );

        if ($shown < $this->total) {
            $text .= sprintf(
                ' Type `/tronald ? %s --page %s` to see more results.',
                $this->query,
                $this->page + 1
            );
        }

        return [
            'icon_url'      => parent::ICON_AVATAR_URL,
            'response_type' => parent::RESPONSE_TYPE_IN_CHANNEL,
            'attachments'   => $attachments ? : null,
            'text'          => $text,
            'mrkdwn'        => true
        ];
    }
}
