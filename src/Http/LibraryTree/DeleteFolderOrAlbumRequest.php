<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\LibraryTree;

use TYPO3Canto\CantoApi\Http\Request;

final class DeleteFolderOrAlbumRequest extends Request
{
    private array $folder;

    public function addFolder(string $id, string $scheme): self
    {
        $this->folder[$id] = [
            'id' => $id,
            'scheme' => $scheme,
        ];
        return $this;
    }

    public function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return array_values($this->folder);
    }

    public function getApiPath(): string
    {
        return 'batch/folder';
    }

    public function getMethod(): string
    {
        return self::DELETE;
    }
}
