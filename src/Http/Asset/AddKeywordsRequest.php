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

final class AddKeywordsRequest extends Request
{
    private string $name;
    private string $description;

    public function __construct(string $name, string $description)
    {
        $this->name = $name;
        $this->description = $description;
    }

    protected function hasBody(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function getApiPath(): string
    {
        return 'keyword';
    }

    public function getMethod(): string
    {
        return self::POST;
    }
}
