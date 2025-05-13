<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Asset;

use Psr\Http\Message\ResponseInterface;

class BatchUpdatePropertiesResponse implements \TYPO3Canto\CantoApi\Http\ResponseInterface
{
    protected string $body;

    public function __construct(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $this->body = $response->getBody()->getContents();
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isSuccessful(): bool
    {
        return $this->body === 'success';
    }
}
