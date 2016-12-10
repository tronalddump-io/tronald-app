<?php

/**
 * QuoteBroker.php - created 12 Nov 2016 14:24:07
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
 * QuoteBroker
 *
 * @package Tronald\Lib
 */
class QuoteBroker extends AbstractBroker
{

    /**
     *
     * @param  Entity\Quote $quote
     * @return Entity\Quote
     */
    public function create(Entity\Quote $quote)
    {
        $authorId = $quote->getAuthor() instanceof Entity\Author ? $quote->getAuthor()->getId() : null;
        $sourceId = $quote->getSource() instanceof Entity\QuoteSource ? $quote->getSource()->getId() : null;

        if (!$quote->getAppearedAt() || !$authorId || !$sourceId) {
            throw new Exception\InvalidArgumentException('The following values must be non-empty ("appeared_at", "author_id", "source_id").');
        }

        $quoteData             = $this->entityFactory->toArray($quote);
        $quoteData['quote_id'] = $quoteData['quote_id'] ? $quoteData['quote_id'] : Util::createSlugUuid();

        try {
            $this->database->beginTransaction();

            $this->database->fetchColumn(
                'INSERT INTO quote (appeared_at, author_id, tags, quote_id, quote_source_id, value)
                VALUES (:appeared_at, :author_id, :tags, :quote_id, :quote_source_id, :value);',
                [
                    'appeared_at'     => $quoteData['appeared_at'],
                    'author_id'       => $authorId,
                    'tags'            => is_array($quoteData['tags']) ? json_encode($quoteData['tags']) : null,
                    'quote_id'        => $quoteData['quote_id'],
                    'quote_source_id' => $sourceId,
                    'value'           => $quoteData['value'],
                ]
            );
            $this->database->commit();

        } catch (Exception $exception) {
            $this->database->rollBack();

            throw new Exception\DatabaseException(null, null, $exception);
        }

        return $this->get($quoteData['quote_id']);
    }

    /**
     *
     * @param  string $id
     * @return Entity\Quote
     */
    public function get($id)
    {
        if (! $res = $this->database->fetchColumn('SELECT get_quote(:quote_id);', [ 'quote_id' => $id ])) {
            throw new Exception\NotFoundException(sprintf('Could not find quote with id "%s".', $id));
        }

        return $this->entityFactory->fromJson(Entity\Quote::class, $res);
    }

    /**
     *
     * @param  string  $tag
     * @param  integer $limit
     * @param  integer $offset
     * @return null|Entity\Quote[]
     */
    public function findByTag($tag, $limit = null, $offset = 0)
    {
        $res = json_decode($this->database->fetchColumn(
            'SELECT find_quote_by_tag(:tag, :options);',
            [
                'tag'     => $tag,
                'options' => json_encode([ 'limit'  => $limit, 'offset' => $offset ])
            ]
        ), true);

        if ($res['result']) {
            foreach ($res['result'] as $index => $row) {
                $res['result'][$index] = $this->entityFactory->fromArray(Entity\Quote::class, $row);
            }
        }

        return [
            'total'  => $res['total'],
            'result' => $res['result']
        ];
    }

    /**
     *
     * @return array
     */
    public function findTags()
    {
        $rows = $this->database->fetchList(
            'SELECT
                tags,
                count(quote_id) AS "count"
             FROM
                quote
             WHERE
                tags IS NOT NULL
             GROUP BY
                tags
             ORDER BY
                count(quote_id) DESC');

        $response = [];
        foreach ($rows as $row) {
            $tags = json_decode($row['tags'], true);
            foreach ($tags as $tag) {
                array_push($response, (object) [ 'name' => $tag, 'count' => $row['count'] ]);
            }
        }

        return !empty($response) ? $response : [];
    }

    /**
     *
     * @param  string $tag
     * @return Entity\Quote
     */
    public function random($tag = null)
    {
        if (! $res = $this->database->fetchColumn('SELECT get_quote_random(:tag);', [ 'tag' => $tag ])) {
            throw new Exception\NotFoundException('Could not find a random quote.');
        }

        return $this->entityFactory->fromJson(Entity\Quote::class, $res);
    }

    /**
     *
     * @param  string  $query
     * @param  integer $limit
     * @param  integer $offset
     * @return null|Entity\Quote[]
     */
    public function search($query, $limit = null, $offset = 0)
    {
        $res = json_decode($this->database->fetchColumn(
            'SELECT find_quote_by_query(:query, :options);',
            [
                'query'   => $query,
                'options' => json_encode([ 'limit'  => $limit, 'offset' => $offset ])
            ]
        ), true);

        if ($res['result']) {
            foreach ($res['result'] as $index => $row) {
                $res['result'][$index] = $this->entityFactory->fromArray(Entity\Quote::class, $row);
            }
        }

        return [
            'total'  => $res['total'],
            'result' => $res['result']
        ];
    }
}
