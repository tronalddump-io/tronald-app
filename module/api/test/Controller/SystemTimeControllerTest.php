<?php

/**
  * SystemTimeControllerTest.php - created 11 Dec 2016 09:40:34
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\Test\App\Api\Controller;

use Tronald\App\Api\Controller\SystemTimeController;

/**
 *
 * SystemTimeControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class SystemTimeControllerTest extends AbstractControllerTest
{

    /**
     *
     * @covers Tronald\App\Api\Controller\SystemTimeController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/system/time');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/get.json');
    }
}
