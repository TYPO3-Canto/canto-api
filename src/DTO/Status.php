<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\DTO;

final class Status
{
    public const STATUS_DONE = 'Done';
    public const STATUS_INITIAL = 'Initial';
    public const STATUS_ERROR = 'Error';

    public string $name;
    public string $time;
    public string $status;
    public ?string $scheme;
    public ?string $id;

    public static function fromResultItem(array $resultItem): self
    {
        $self = new self();

        $self->scheme = $resultItem['scheme'] ?? null;
        $self->name = $resultItem['name'] ?? '';
        $self->time = $resultItem['time'] ?? '';
        $self->id = $resultItem['id'] ?? null;
        $self->status = $resultItem['status'] ?? '';

        return $self;
    }
}
