<?php

/**
 * AbstractControllerTest.php - created 10 Dec 2016 11:37:22
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Test\App\Api\Controller;

use League\JsonGuard;
use Symfony\Component\HttpFoundation;

/**
 *
 * AbstractControllerTest
 *
 * @package Tronald\Test\App\Api
 */
abstract class AbstractControllerTest extends \Silex\WebTestCase
{
    /**
     *
     * @param  HttpFoundation\Response|string $data
     * @param  string                         $schema
     * @throws \Runtime
     * @throws \InvalidArgumentException
     * @return void
     */
    public function assertJsonSchema($data, $schemaKey)
    {
        $deref  = new JsonGuard\Dereferencer();
        $schema = $deref->dereference(
            sprintf('file://%s/schema/%s', APPLICATION_PATH, $schemaKey)
        );

        if ($data instanceof HttpFoundation\Response) {
            $data = json_decode($data->getContent());
            if (json_last_error()) {
                throw new \RuntimeException('Argument data could not be decoded.');
            }

        } else if (is_string($data)) {
            $data = $data;
        } else {
            throw new \InvalidArgumentException('Invalid data type for argument "data" given.');
        }

        $validator = new JsonGuard\Validator($data, $schema);
        if (empty($errors = $validator->errors())) {
            $this->assertTrue(true, 'Data structure matches json schema.');
            return null;
        }

        foreach ($errors as $error) {
            $message = sprintf('Json schema validation error in schema "%s". %s', $schemaKey, $error->getMessage());

            throw new \PHPUnit_Framework_ExpectationFailedException($message);
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see    \Silex\WebTestCase::createApplication()
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $path = realpath(__DIR__ . '/../../src/');
        $app  = include sprintf('%s/App.php', $path);

        return $app;
    }
}
