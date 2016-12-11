<?php

/**
  * IndexControllerTest.php - created 10 Dec 2016 14:13:03
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\Test\App\Api\Controller;

use Tronald\App\Api\Controller\IndexController;

/**
 *
 * IndexControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class IndexControllerTest extends AbstractControllerTest
{

    /**
     *
     * @covers Tronald\App\Api\Controller\IndexController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/get.json');
    }
}
