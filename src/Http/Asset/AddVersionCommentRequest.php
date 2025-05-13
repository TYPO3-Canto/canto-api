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

class AddVersionCommentRequest extends Request
{
    private string $scheme;
    private string $id;
    private string $versionId;
    private string $comment;

    public function __construct(string $scheme, string $id, string $versionId, string $comment)
    {
        $this->scheme = $scheme;
        $this->id = $id;
        $this->versionId = $versionId;
        $this->comment = $comment;
    }

    public function jsonSerialize(): array
    {
        return [
            'scheme' => $this->scheme,
            'id' => $this->id,
            'versionId' => $this->versionId,
            'comment' => $this->comment,
        ];
    }

    public function getApiPath(): string
    {
        return 'version/comment';
    }

    public function getMethod(): string
    {
        return self::POST;
    }

    protected function hasBody(): bool
    {
        return true;
    }
}
