<?php

/**
 * QuoteFormatter.php - created 29 Nov 2016 07:15:45
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Formatter\Hal;

use Nocarrier\Hal;
use Symfony\Component\Routing;

/**
 *
 * QuoteFormatter
 *
 * @package    Tronald\Lib
 * @subpackage Formatter
 */
class QuoteFormatter extends AbstractHalFormatter implements HalFormatterInterface
{
    /**
     *
     * @var AuthorFormatter
     */
    protected $authorFormatter;

    /**
     *
     * @var QuoteSourceFormatter
     */
    protected $quoteSourceFormatter;

    /**
     *
     * @param Routing\Generator\UrlGenerator $urlGenerator
     * @param AuthorFormatter $authorFormatter
     * @param QuoteSourceFormatter $quoteSourceFormatter
     */
    public function __construct(
        Routing\Generator\UrlGenerator $urlGenerator,
        AuthorFormatter $authorFormatter,
        QuoteSourceFormatter $quoteSourceFormatter
    ) {
        parent::__construct($urlGenerator);

        $this->authorFormatter = $authorFormatter;
        $this->quoteSourceFormatter = $quoteSourceFormatter;
    }

    /**
     *
     * {@inheritDoc}
     * @see \Tronald\Lib\Formatter\Hal\HalFormatterInterface::toHal()
     */
    public function toHal($data)
    {
        $author = $this->authorFormatter->toHal($data['author']);
        $source = $this->quoteSourceFormatter->toHal($data['source']);

        unset($data['author'], $data['source']);

        $hal = new Hal(
            $this->urlGenerator->generate('api.get_quote', [ 'id' => $data['quote_id']]),
            $data
        );

        $hal->addResource('autor', $author);
        $hal->addResource('source', $source);

        return $hal;
    }
}
