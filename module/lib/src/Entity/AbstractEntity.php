<?php

/**
 * AbstractEntity.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Entity;

/**
 *
 * AbstractEntity
 *
 * @package Tronald\Lib
 *
 */
abstract class AbstractEntity
{

    /**
     *
     * @var string
     */
    protected static $className;

    /**
     * @return void
     */
    final public function __construct()
    {
    }

    /**
     *
     * @param  string $name
     * @param  array  $arguments
     * @return mixed
     */
    final public function __call($name, array $arguments = [])
    {
        if ('get' === substr($name, 0, 3)) {
            return $this->__get(lcfirst(substr($name, 3)));
        }

        if ('has' === substr($name, 0, 3)) {
            return $this->__get(lcfirst($name) ?: false);
        }

        if ('is' === substr($name, 0, 2)) {
            return $this->__get(lcfirst(substr($name, 2)) ?: false);
        }

        if ('set' === substr($name, 0, 3)) {
            return $this->__set(lcfirst(substr($name, 3)), $arguments[0]);
        }
    }

    /**
     *
     * @param  string $name
     * @return mixed
     */
    final public function __get($name)
    {
        return $this->$name;
    }

    /**
     *
     * @return void
     */
    final public function __set($name, $value)
    {
        $this->$name = $value;
        return $this;
    }
}
