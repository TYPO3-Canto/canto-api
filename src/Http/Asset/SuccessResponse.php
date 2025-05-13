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

final class SuccessResponse implements \TYPO3Canto\CantoApi\Http\ResponseInterface
{
    private bool $success;

    public function __construct(ResponseInterface $response)
    {
        $response->getBody()->rewind();
        $this->success = $response->getBody()->getContents() === 'success';
    }

    public function isSuccessful(): bool
    {
        return $this->success;
    }
}
