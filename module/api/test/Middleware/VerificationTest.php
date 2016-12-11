<?php

/**
 * VerificationTest.php - created 11 Dec 2016 10:26:21
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Test\App\Api\Middleware;

use Tronald\App\Api\Middleware\Verification;
use Silex\Application;
use Symfony\Component\HttpFoundation;

/**
 *
 * VerificationTest
 *
 * @package Tronald\Test\App\Api
 */
class VerificationTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     * @return \Silex\Application
     */
    private function getApplication()
    {
        $app = new Application();
        $app['slack'] = [
            'verification_token' => 'foo'
        ];

        return $app;
    }

    /**
     *
     * @expectedException        Tronald\Lib\Exception\SlackVerificationTokenException
     * @expectedExceptionMessage Could not verify "SLACK_VERIFICATION_TOKEN".
     * @covers Tronald\App\Api\Middleware\Verification::slackOrigin
     */
    public function testSlackOriginFailure()
    {
        $request = new HttpFoundation\Request([], [
            'token' => 'bar'
        ]);

        Verification::slackOrigin($request, $this->getApplication());
    }

    /**
     *
     * @covers Tronald\App\Api\Middleware\Verification::slackOrigin
     */
    public function testSlackOriginSuccess()
    {
        $request = new HttpFoundation\Request([], [
            'token' => 'foo'
        ]);

        $this->assertNull(
            Verification::slackOrigin($request, $this->getApplication())
        );
    }
}
