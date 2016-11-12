<?php

/**
 * Author.php - created 12 Nov 2016 18:29:29
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
 * Author
 *
 * @package Tronald\Lib
 *
 * @method DateTime getCreatedAt
 * @method string   getBio
 * @method string   getId
 * @method string   getName
 * @method string   getSlug
 * @method DateTime getUpdatedAt
 */
class Author extends AbstractEntity
{

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("created_at")
     * @var DateTime
     */
    protected $createdAt;

    /**
     *
     * @SerializeType("string") @SerializeName("bio")
     * @var string
     */
    protected $bio;

    /**
     *
     * @SerializeType("string") @SerializeName("author_id")
     * @var string
     */
    protected $id;

    /**
     *
     * @SerializeType("string") @SerializeName("name")
     * @var string
     */
    protected $name;

    /**
     *
     * @SerializeType("string") @SerializeName("slug")
     * @var string
     */
    protected $slug;

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("updated_at")
     * @var DateTime
     */
    protected $updatedAt;
}
