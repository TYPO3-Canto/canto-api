<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Http\Upload;

use TYPO3Canto\CantoApi\Http\Request;

final class GetUploadSettingRequest extends Request
{
    private bool $refer;

    public function __construct(bool $refer = false)
    {
        $this->refer = $refer;
    }

    public function getQueryParams(): ?array
    {
        return [
            'refer' => $this->refer,
        ];
    }

    public function getApiPath(): string
    {
        return 'upload/setting';
    }

    public function getMethod(): string
    {
        return self::GET;
    }
}
