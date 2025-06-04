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

final class DeleteAlbumFolderResponse extends Response
{
    private array $permissionFail;
    private array $deleteFail;
    private string $status;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseData = $this->parseResponse($response);
        $this->deleteFail = $responseData['deleteFail'] ?? [];
        $this->permissionFail = $responseData['permissionFail'] ?? [];
        $this->status = $responseData['status'];
    }

    /**
     * A list of ids, which failed to delete, because of missing permissions.
     * @return string[]
     */
    public function getPermissionFail(): array
    {
        return $this->permissionFail;
    }

    /**
     * A list of ids, which failed to delete, because of an error during deletion.
     * @return string[]
     */
    public function getDeleteFail(): array
    {
        return $this->deleteFail;
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
