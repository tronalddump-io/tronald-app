<?php

/**
 * Quote.php - created 12 Nov 2016 18:29:29
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Entity;

use DateTime;
use \JMS\Serializer\Annotation\Type as SerializeType;
use \JMS\Serializer\Annotation\SerializedName as SerializeName;

/**
 *
 * Quote
 *
 * @package Tronald\Lib
 *
 * @method string      getAppearedAt
 * @method Author      getAuthor
 * @method string      getCreatedAt
 * @method string      getId
 * @method array       getTags
 * @method QuoteSource getSource
 * @method string      getUpdatedAt
 * @method string      getValue
 */
class Quote extends AbstractEntity
{

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s'>") @SerializeName("appeared_at")
     * @var DateTime
     */
    protected $appearedAt;

    /**
     *
     * @SerializeType("Tronald\Lib\Entity\Author") @SerializeName("author")
     * @var Author
     */
    protected $author;

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("created_at")
     * @var DateTime
     */
    protected $createdAt;

    /**
     *
     * @SerializeType("string") @SerializeName("quote_id")
     * @var string
     */
    protected $id;

    /**
     *
     * @SerializeType("array") @SerializeName("tags")
     * @var array
     */
    protected $tags;

    /**
     *
     * @SerializeType("Tronald\Lib\Entity\QuoteSource") @SerializeName("source")
     * @var QuoteSource
     */
    protected $source;

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("updated_at")
     * @var DateTime
     */
    protected $updatedAt;

    /**
     *
     * @SerializeType("string") @SerializeName("value")
     * @var string
     */
    protected $value;
}
