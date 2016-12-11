<?php

/**
  * SearchQuoteControllerTest.php - created 11 Dec 2016 09:44:31
  *
  * @copyright Copyright (c) Mathias Schilling <m@matchilling>
  *
  * For the full copyright and license information, please view the LICENSE
  * file that was distributed with this source code.
  *
  */
namespace Tronald\Test\App\Api\Controller;

use Symfony\Component\HttpFoundation;
use Tronald\App\Api\Controller\SearchQuoteController;

/**
 *
 * SearchQuoteControllerTest
 *
 * @package Tronald\Test\App\Api
 */
class SearchQuoteControllerTest extends AbstractControllerTest
{

    /**
     *
     * @covers Tronald\App\Api\Controller\SearchQuoteController::getAction
     */
    public function testGetAction()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/search/quote', [
            'query' => 'hillary'
        ]);
        $response = $client->getResponse();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('content-type', $response->headers->all());
        $this->assertEquals('application/hal+json', $response->headers->get('content-type'));
        $this->assertJsonSchema($client->getResponse(), 'api/search/quote/get.json');
    }

    /**
     *
     * @covers Tronald\App\Api\Controller\SearchQuoteController::getAction
     */
    public function testGetActionMinQueryLengthParam()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/search/quote', [
            'query' => 'xx'
        ]);
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
            'Parameter "query" must have minimum length of 3 characters.',
            $content->message
        );
    }

    /**
     *
     * @covers Tronald\App\Api\Controller\SearchQuoteController::getAction
     */
    public function testGetActionWithEmptyQueryParam()
    {
        $client   = $this->createClient();
        $crawler  = $client->request('GET', '/search/quote', [
            'query' => ''
        ]);
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
            'Parameter "query" must be a non-empty string.',
            $content->message
        );
    }
}
