<?php

/**
 * AuthorBroker.php - created 12 Nov 2016 14:24:07
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
 * AuthorBroker
 *
 * @package Tronald\Lib
 */
class AuthorBroker extends AbstractBroker
{
    /**
     *
     * @param  Entity\Author $author
     * @throws Exception\DatabaseException
     * @return Entity\Author
     */
    public function create(Entity\Author $author)
    {
        $authorData = [
            'author_id' => $author->getId() ? $author->getId() : Util::createSlugUuid(),
            'bio'       => $author->getBio(),
            'name'      => $author->getName(),
            'slug'      => $author->getSlug() ? $author->getSlug() : Util::slugify($author->getName())
        ];

        try {
            $this->database->beginTransaction();
            $this->database->fetchColumn(
                'INSERT INTO author (author_id, bio, name, slug) VALUES (:author_id, :bio, :name, :slug);',
                $authorData
            );
            $this->database->commit();

        } catch (Exception $exception) {
            $this->database->rollBack();

            throw new Exception\DatabaseException(null, null, $exception);
        }

        return $this->get($authorData['author_id']);
    }

    /**
     *
     * @param  string $id
     * @return boolean
     */
    public function delete($id)
    {
        $response = $this->database->fetchRecord(
            'DELETE FROM author WHERE author_id = :author_id;', [ 'author_id' => $id ]
        );

        return is_array($response);
    }

    /**
     *
     * @param  string $id
     * @throws Exception\NotFoundException
     * @return Entity\Author
     */
    public function get($id)
    {
        if (! $res = $this->database->fetchColumn('SELECT get_author(:id);', [ 'id' => $id ])) {
            throw new Exception\NotFoundException(sprintf('Could not find author source with id "%s".', $id));
        }

        return $this->entityFactory->fromJson(Entity\Author::class, $res);
    }

    /**
     *
     * @param  string $slug
     * @throws Exception\NotFoundException
     * @return Entity\Author
     */
    public function findBySlug($slug)
    {
        if (! $res = $this->database->fetchColumn('SELECT find_author_by_slug(:slug);', [ 'slug' => $slug ])) {
            throw new Exception\NotFoundException(sprintf('Could not find author source with slug "%s".', $slug));
        }

        return $this->entityFactory->fromJson(Entity\Author::class, $res);
    }
}
