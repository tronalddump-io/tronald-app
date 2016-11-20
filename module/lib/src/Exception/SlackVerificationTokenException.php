<?php

/**
 * SlackVerificationTokenException.php - created 20 Nov 2016 20:18:33
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib\Exception;

use \Symfony\Component\HttpKernel\Exception as Exception;

/**
 *
 * SlackVerificationTokenException
 *
 * @package Tronald\Lib
 *
 */
class SlackVerificationTokenException extends Exception\PreconditionFailedHttpException
{
    /**
     *
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct(
            'Could not verify "SLACK_VERIFICATION_TOKEN".',
            $previous = null,
            $code
        );
    }
}
