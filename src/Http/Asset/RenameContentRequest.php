<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Asset;

final class RenameContentRequest extends \TYPO3Canto\CantoApi\Http\Request
{
    private string $scheme;
    private string $contentId;
    private string $newName;

    public function __construct(string $scheme, string $contentId, string $newName)
    {
        $this->scheme = $scheme;
        $this->contentId = $contentId;
        $this->newName = $newName;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->scheme,
            $this->contentId,
            'rename',
        ];
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->newName,
        ];
    }

    public function getApiPath(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return self::PUT;
    }
}
