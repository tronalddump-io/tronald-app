<?php

/**
 * QuoteSource.php - created 12 Nov 2016 18:29:29
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
 * QuoteSource
 *
 * @package Tronald\Lib
 *
 * @method string getCreatedAt
 * @method string getFilename
 * @method string getId
 * @method string getRemarks
 * @method string getUpdatedAt
 * @method string getUrl
 */
class QuoteSource extends AbstractEntity
{

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("created_at")
     * @var DateTime
     */
    protected $createdAt;

    /**
     *
     * @SerializeType("string") @SerializeName("filename")
     * @var string
     */
    protected $filename;

    /**
     *
     * @SerializeType("string") @SerializeName("quote_source_id")
     * @var string
     */
    protected $id;

    /**
     *
     * @SerializeType("string") @SerializeName("remarks")
     * @var string
     */
    protected $remarks;

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("updated_at")
     * @var DateTime
     */
    protected $updatedAt;

    /**
     *
     * @SerializeType("string") @SerializeName("url")
     * @var string
     */
    protected $url;
}
