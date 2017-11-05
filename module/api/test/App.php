<?php

/**
 * TagsControllerTest.php - created 05 Nov 2017 10:10:01
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Test\App\Api;

/**
 *
 * AppTest
 *
 * @package Tronald\Test\App\Api
 */
class AppTest extends \Silex\WebTestCase
{
    /**
     *
     * {@inheritDoc}
     * @see    \Silex\WebTestCase::createApplication()
     * @return \Silex\Application
     */
    public function createApplication()
    {
        $path = realpath(__DIR__ . '/../src/');
        $app  = include sprintf('%s/App.php', $path);

        return $app;
    }

    public function testTagsRedirectAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/tags');
        $response = $client->getResponse();

        $this->assertEquals(301, $response->getStatusCode());
    }
}
