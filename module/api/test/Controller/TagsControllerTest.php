<?php

/**
 * TagsControllerTest.php - created 10 Dec 2016 11:25:35
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Test\App\Api\Controller;

use Tronald\App\Api\Controller\TagsController;

/**
 *
 * TagsControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class TagsControllerTest extends AbstractControllerTest
{
    /**
     *
     * @covers Tronald\App\Api\Controller\TagsController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/tag');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/hal+json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/tag/get.json');
    }

    /**
     *
     * @covers Tronald\App\Api\Controller\TagsController::getTagAction
     */
    public function testGetTagAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/tag/Hillary%20Clinton');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/hal+json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/tag/id/get.json');
    }
}
