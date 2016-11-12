<?php

/**
 * AbstractBroker.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Broker;

use Tronald\Lib;
use Tronald\Lib\Entity;

/**
 *
 * AbstractBroker
 *
 * @package Tronald\Lib
 *
 */
abstract class AbstractBroker
{

    /**
     *
     * @var Lib\Database
     */
    protected $database;

    /**
     *
     * @var Entity\Factory
     */
    protected $entityFactory;

    /**
     *
     * @return void
     */
    public function __construct(Lib\Database $database, Entity\Factory $entityFactory)
    {
        $this->database      = $database;
        $this->entityFactory = $entityFactory;
    }
}
