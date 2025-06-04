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

class GetTreeResponse extends Response
{
    protected array $results;

    protected string $sortBy;

    protected string $sortDirection;

    /**
     * @throws InvalidResponseException
     */
    public function __construct(ResponseInterface $response)
    {
        $responseData = $this->parseResponse($response);

        $this->results = $responseData['results'] ?? [];
        $this->sortBy = $responseData['sortBy'];
        $this->sortDirection = $responseData['sortDirection'];
    }

    public function getResults(): array
    {
        return $this->results;
    }

    public function getSortBy(): string
    {
        return $this->sortBy;
    }

    public function getSortDirection(): string
    {
        return $this->sortDirection;
    }
}
