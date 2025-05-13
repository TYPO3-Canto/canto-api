<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Helper;

use TYPO3Canto\CantoApi\Client;
use TYPO3Canto\CantoApi\Endpoint\Authorization\NotAuthorizedException;
use TYPO3Canto\CantoApi\Http\Asset\GetContentDetailsRequest;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;

final class MdcUrlHelper
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $assetId
     * @param string $scheme
     * @return array{width: int, height: int, scale: float}
     * @throws NotAuthorizedException
     * @throws InvalidResponseException
     */
    public function scaleImageByMasterImageSize(string $assetId, string $scheme = 'image'): array
    {
        $request = new GetContentDetailsRequest($assetId, $scheme);
        $response = $this->client->asset()->getContentDetails($request);
        $width = (int)$response->getResponseData()['width'];
        $height = (int)$response->getResponseData()['height'];
        return $this->scaleWidthHeightByMasterImageSize($width, $height);
    }

    /**
     * @param int $width
     * @param int $height
     * @return array{width: int, height: int, scale: float}
     */
    public function scaleWidthHeightByMasterImageSize(int $width, int $height): array
    {
        $masterImageSize = $this->client->getOptions()->getMasterImageSize();
        /** @var int|float $scale */
        $scale = min(1, $masterImageSize / $width, $masterImageSize / $height);
        return [
            'width' => (int)($scale * $width),
            'height' => (int)($scale * $height),
            'scale' => $scale,
        ];
    }
}
