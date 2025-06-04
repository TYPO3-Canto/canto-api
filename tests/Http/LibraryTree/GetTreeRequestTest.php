<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Http\LibraryTree;

use PHPUnit\Framework\TestCase;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetTreeRequest;

class GetTreeRequestTest extends TestCase
{
    /**
     * @test
     */
    public function createRequestWithDefaultConfig(): void
    {
        $request = new GetTreeRequest();
        $expected = [
            'sortBy' => 'time',
            'sortDirection' => 'ascending',
            'layer' => -1,
        ];

        self::assertSame($expected, $request->getQueryParams());
        self::assertNull($request->getPathVariables());
    }

    /**
     * @test
     */
    public function setSort(): void
    {
        $request = new GetTreeRequest();
        $request->setSortBy('name');

        self::assertSame('name', $request->getQueryParams()['sortBy']);
    }

    /**
     * @test
     */
    public function setSortDirection(): void
    {
        $request = new GetTreeRequest();
        $request->setSortDirection('descending');

        self::assertSame('descending', $request->getQueryParams()['sortDirection']);
    }

    /**
     * @test
     */
    public function setLayer(): void
    {
        $request = new GetTreeRequest();
        $request->setLayer(3);

        self::assertSame(3, $request->getQueryParams()['layer']);
    }
}
