<?php

/**
 * User.php - created 3 Dec 2016 18:23:16
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
 * User
 *
 * @package Tronald\Lib
 *
 * @method string  getCreatedAt
 * @method string  getEmail
 * @method string  getId
 * @method string  getUpdatedAt
 * @method string  getUsername
 * @method boolean isVerified
 */
class User extends AbstractEntity
{

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("created_at")
     * @var DateTime
     */
    protected $createdAt;

    /**
     *
     * @SerializeType("string") @SerializeName("email")
     * @var string
     */
    protected $email;

    /**
     *
     * @SerializeType("string") @SerializeName("user_id")
     * @var string
     */
    protected $id;

    /**
     *
     * @SerializeType("DateTime<'Y-m-d\TH:i:s.u'>") @SerializeName("updated_at")
     * @var DateTime
     */
    protected $updatedAt;

    /**
     *
     * @SerializeType("string") @SerializeName("username")
     * @var string
     */
    protected $username;

    /**
     *
     * @SerializeType("boolean") @SerializeName("verified")
     * @var boolean
     */
    protected $verified;
}
