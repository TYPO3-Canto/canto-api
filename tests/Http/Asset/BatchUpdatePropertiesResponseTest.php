<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Http\Asset;

use TYPO3Canto\CantoApi\Http\Asset\BatchUpdatePropertiesResponse;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class BatchUpdatePropertiesResponseTest extends TestCase
{
    /**
     * @test
     */
    public function createValidResponse(): void
    {
        $httpResponse = new Response(200, [], 'success');
        $response = new BatchUpdatePropertiesResponse($httpResponse);

        self::assertSame('success', $response->getBody());
    }
}
