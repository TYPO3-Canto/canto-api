<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Upload;

use Psr\Http\Message\ResponseInterface;
use TYPO3Canto\CantoApi\DTO\Status;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\Response;

final class QueryUploadStatusResponse extends Response
{
    /**
     * @var array<array{name: string, time: string, status: string}>
     */
    private array $results;
    private int $found;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $result = $this->parseResponse($response);
        $this->found = (int)($result['found'] ?? 0);
        $this->results = $result['results'] ?? [];
    }

    /**
     * @return array<array{name: string, time: string, status: string}>
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @return array<Status>
     */
    public function getStatusItems(): array
    {
        return array_map(static fn(array $item) => Status::fromResultItem($item), $this->results);
    }

    public function getFound(): int
    {
        return $this->found;
    }
}
