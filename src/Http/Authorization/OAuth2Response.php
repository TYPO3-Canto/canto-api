<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Authorization;

use Psr\Http\Message\ResponseInterface;
use TYPO3Canto\CantoApi\Endpoint\Authorization\AuthorizationFailedException;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\Response;

class OAuth2Response extends Response
{
    private string $accessToken;

    private int $expiresIn;

    private string $tokenType;

    private ?string $refreshToken;

    /**
     * @throws AuthorizationFailedException
     */
    public function __construct(ResponseInterface $response)
    {
        try {
            $json = $this->parseResponse($response);
        } catch (InvalidResponseException $e) {
            throw new AuthorizationFailedException(
                'Authorization failed - ' . $e->getMessage(),
                1626449779,
                $e
            );
        }

        $this->accessToken = $json['accessToken'] ?? '';
        $this->expiresIn = (int)($json['expiresIn'] ?? 0);
        $this->tokenType = $json['tokenType'] ?? '';
        $this->refreshToken = $json['refreshToken'] ?? null;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getRefreshToken(): ?string
    {
        return $this->refreshToken;
    }
}
