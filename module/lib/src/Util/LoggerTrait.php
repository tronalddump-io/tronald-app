<?php

/**
 * LoggerTrait.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Util;

/**
 *
 * LoggerTrait
 *
 * @package Tronald\Lib
 *
 */
trait LoggerTrait
{

    /**
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logAlert($message, array $context = [])
    {
        return $this->logger->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logCritical($message, array $context = [])
    {
        return $this->logger->critical($message, $context);
    }

    /**
     * Detailed debug information.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logDebug($message, array $context = [])
    {
        return $this->logger->debug($message, $context);
    }

    /**
     * System is unusable.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logEmergency($message, array $context = [])
    {
        return $this->logger->emergency($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logError($message, array $context = [])
    {
        return $this->logger->error($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logInfo($message, array $context = [])
    {
        return $this->logger->info($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logNotice($message, array $context = [])
    {
        return $this->logger->notice($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param  string $message
     * @param  array  $context
     * @return null
     */
    public function logWarning($message, array $context = [])
    {
        return $this->logger->warning($message, $context);
    }

    /**
     * Set the logger
     *
     * @param  \Psr\Log\LoggerInterface $logger
     * @return void
     */
    protected function setLogger(\Psr\Log\LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
