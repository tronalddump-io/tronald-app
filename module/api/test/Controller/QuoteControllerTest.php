<?php

/**
  * QuoteControllerTest.php - created 10 Dec 2016 14:31:08
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\Test\App\Api\Controller;

use Tronald\App\Api\Controller\QuoteController;

/**
 *
 * QuoteControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class QuoteControllerTest extends AbstractControllerTest
{

    /**
     *
     * @covers Tronald\App\Api\Controller\QuoteController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/quote/VHKwB8crTte7--FqtIxq9A');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/hal+json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/quote/get.json');
    }
}
