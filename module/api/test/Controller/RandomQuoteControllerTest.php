<?php

/**
  * RandomQuoteControllerTest.php - created 11 Dec 2016 08:38:42
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\Test\App\Api\Controller;

use Symfony\Component\HttpFoundation;
use Tronald\App\Api\Controller\RandomQuoteController;

/**
 *
 * RandomQuoteControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class RandomQuoteControllerTest extends AbstractControllerTest
{

    /**
     *
     * @covers Tronald\App\Api\Controller\RandomQuoteController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/random/quote');
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/hal+json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/random/quote/get.json');
    }

    /**
     *
     * @covers Tronald\App\Api\Controller\RandomQuoteController::getAction
     */
    public function testGetActionWithEmptyTagParam()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/random/quote?tag=');
        $response = $client->getResponse();
        $content  = json_decode($response->getContent());

        $this->assertEquals(
            HttpFoundation\Response::HTTP_PRECONDITION_FAILED,
            $response->getStatusCode()
        );

        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/json', $response->headers->get('content-type'));

        $this->assertEquals(
            HttpFoundation\Response::HTTP_PRECONDITION_FAILED,
            $content->status
        );

        $this->assertEquals(
            'Parameter "tag" must be a non empty string.',
            $content->message
        );
    }
}
