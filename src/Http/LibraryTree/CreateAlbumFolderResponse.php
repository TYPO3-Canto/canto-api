<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\LibraryTree;

use Psr\Http\Message\ResponseInterface;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\Response;

final class CreateAlbumFolderResponse extends Response
{
    private array $responseData;
    private string $id;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $this->responseData = $this->parseResponse($response);
        $this->id = $this->responseData['id'];
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
