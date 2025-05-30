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
use TYPO3Canto\CantoApi\Http\LibraryTree\SearchFolderResponse;

class SearchFolderResponseTest extends TestCase
{
    /**
     * @test
     */
    public function createValidResponse(): void
    {
        $responseBody = '{' .
            '"facets":[{"key":"facet-value"}],' .
            '"results":[{"result-id":1234}],' .
            '"limit":100,' .
            '"found":500,' .
            '"sortBy":"name",' .
            '"sortDirection":"ascending",' .
            '"matchExpr":"test"' .
            '}';
        $response = new SearchFolderResponse(new Response(200, [], $responseBody));

        self::assertSame([['key' => 'facet-value']], $response->getFacets());
        self::assertSame([['result-id' => 1234]], $response->getResults());
        self::assertSame(100, $response->getLimit());
        self::assertSame(500, $response->getFound());
        self::assertSame('name', $response->getSortBy());
        self::assertSame('ascending', $response->getSortDirection());
        self::assertSame('test', $response->getMatchExpr());
    }

    /**
     * @test
     */
    public function throwExceptionWithEmptyBody(): void
    {
        self::expectExceptionCode(1626434956);

        new SearchFolderResponse(new Response(200, [], ''));
    }

    /**
     * @test
     */
    public function throwExceptionWithInvalidJsonBody(): void
    {
        self::expectExceptionCode(1626434988);

        new SearchFolderResponse(new Response(200, [], 'invalid-json'));
    }
}
