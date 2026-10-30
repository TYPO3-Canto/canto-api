<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Http\Upload;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use TYPO3Canto\CantoApi\Http\Upload\GetUploadSettingResponse;

class GetUploadSettingResponseTest extends TestCase
{
    /**
     * @test
     */
    public function metaIdAndFileNameAreMappedToTheirOwnFields(): void
    {
        $response = new GetUploadSettingResponse(new Response(200, [], json_encode([
            'x-amz-meta-id' => 'meta-id-1234',
            'x-amz-meta-file_name' => 'image.jpg',
        ], JSON_THROW_ON_ERROR)));

        self::assertSame('meta-id-1234', $response->getXAmzMetaId());
        self::assertSame('image.jpg', $response->getXAmzMetaFileName());
    }
}
