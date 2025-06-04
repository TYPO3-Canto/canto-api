<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Http\LibraryTree;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetTreeResponse;

class GetTreeResponseTest extends TestCase
{
    /**
     * @test
     */
    public function createValidResponse(): void
    {
        $response = new GetTreeResponse(
            new Response(
                200,
                [],
                '{"results":[{"id":"test"}],"sortBy":"time","sortDirection":"descending"}'
            )
        );

        self::assertSame([['id' => 'test']], $response->getResults());
        self::assertSame('time', $response->getSortBy());
        self::assertSame('descending', $response->getSortDirection());
    }

    /**
     * @test
     */
    public function throwExceptionWithEmptyBody(): void
    {
        self::expectExceptionCode(1626434956);

        new GetTreeResponse(new Response(200, [], ''));
    }

    /**
     * @test
     */
    public function throwExceptionWithInvalidJsonBody(): void
    {
        self::expectExceptionCode(1626434988);

        new GetTreeResponse(new Response(200, [], 'invalid-json'));
    }
}
