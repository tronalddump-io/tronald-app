<?php

/**
 * CommandParser.php - created 20 Nov 2016 20:26:57
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Slack;

use Tronald\Lib\Broker;

/**
 *
 * CommandParser
 *
 * @package Tronald\Lib
 */
class CommandParser
{

    /**
     *
     * @var Broker\QuoteBroker
     */
    private $quoteBroker;

    /**
     *
     * @param Broker\QuoteBroker $quoteBroker
     */
    public function __construct(Broker\QuoteBroker $quoteBroker)
    {
        $this->quoteBroker = $quoteBroker;
    }

    /**
     *
     * @param string $input
     * @retun Slack\AbstractCommand
     */
    public function createCommandString($input)
    {
        $inputWithoutOpt = $this->stripOptions($input);

        if (empty($inputWithoutOpt)) {
            return new Command\RandomQuoteCommand($input, $this->quoteBroker->random());
        }

        if (preg_match('/help(.*)/', $inputWithoutOpt, $matches)) {
            return new Command\HelpCommand($input);
        }

        if (preg_match('/\?(.*)/', $inputWithoutOpt, $matches)) {
            $maxItems = 5;
            $query    = trim($matches[1]);
            $page     = (int) $this->getOptionOrDefault($input, 'page', 1);
            $offset   = $page > 1 ? ($page - 1) * $maxItems : 0;

            $response = $this->quoteBroker->search($query, $maxItems, $offset);

            return new Command\SearchQuoteCommand($input, [
                'total'     => $response['total'],
                'results'   => $response['result'],
                'max_items' => $maxItems,
                'query'     => $query,
                'page'      => $page,
                'offset'    => $offset
            ]);
        }

        return new Command\NotFoundCommand($input);
    }

    /**
     *
     * @param  string $input
     * @param  string $option
     * @param  string $default
     * @return string|null
     */
    private function getOptionOrDefault($input, $option, $default = null)
    {
        $pattern = sprintf('/--%s (\S+)/', $option);

        if (preg_match($pattern, $input, $matches)) {
            return $matches[1];
        }

        return isset($default) ? $default : null;
    }

    /**
     *
     * @param  string $input
     * @return string
     */
    private function stripOptions($input)
    {
        $response = preg_replace('/-(-)?(?-s)(.*)/', '', $input);

        return trim($response);
    }
}
