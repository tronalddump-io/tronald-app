<?php

/**
 * QuoteSourceBroker.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Broker;

use Tronald\Lib\Entity;
use Tronald\Lib\Exception;
use Tronald\Lib\Util;

/**
 *
 * QuoteSourceBroker
 *
 * @package Tronald\Lib
 */
class QuoteSourceBroker extends AbstractBroker
{

    /**
     *
     * @param  Entity\QuoteSource $quoteSource
     * @return Entity\QuoteSource
     */
    public function create(Entity\QuoteSource $quoteSource)
    {
        $quoteSourceData = [
            'filename'        => $quoteSource->getFilename(),
            'quote_source_id' => $quoteSource->getId() ? $quoteSource->getId() : Util::createSlugUuid(),
            'remarks'         => $quoteSource->getRemarks(),
            'url'             => $quoteSource->getUrl()
        ];

        try {
            $this->database->beginTransaction();
            $this->database->fetchColumn(
                'INSERT INTO quote_source (filename, quote_source_id, remarks, url) VALUES (:filename, :quote_source_id, :remarks, :url);',
                $quoteSourceData
            );
            $this->database->commit();

        } catch (Exception $exception) {
            $this->database->rollBack();

            throw new Exception\DatabaseException(null, null, $exception);
        }

        return $this->get($quoteSourceData['quote_source_id']);
    }

    /**
     *
     * @param  string $id
     * @return boolean
     */
    public function delete($id)
    {
        $response = $this->database->fetchRecord(
            'DELETE FROM quote_source WHERE quote_source_id = :quote_source_id;', [ 'quote_source_id' => $id ]
        );

        return is_array($response);
    }

    /**
     *
     * @param  string $id
     * @return Entity\QuoteSource
     */
    public function get($id)
    {
        if (! $res = $this->database->fetchColumn('SELECT get_quote_source(:id);', [ 'id' => $id ])) {
            throw new Exception\NotFoundException(sprintf('Could not find quote source with id "%s".', $id));
        }

        return $this->entityFactory->fromJson(Entity\QuoteSource::class, $res);
    }
}
