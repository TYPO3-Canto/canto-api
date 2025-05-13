<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Asset;

use TYPO3Canto\CantoApi\Http\Request;

final class RemoveTagFromContentRequest extends Request
{
    private string $scheme;
    private string $contentId;
    private string $tag;

    public function __construct(string $scheme, string $contentId, string $tag)
    {
        $this->scheme = $scheme;
        $this->contentId = $contentId;
        $this->tag = $tag;
    }

    public function getPathVariables(): ?array
    {
        return [
            $this->scheme,
            $this->contentId,
            'tag',
            $this->tag,
        ];
    }

    public function getApiPath(): string
    {
        return '';
    }

    public function getMethod(): string
    {
        return self::DELETE;
    }
}
