<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi;

use GuzzleHttp\ClientInterface;
use JetBrains\PhpStorm\ArrayShape;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
class ClientOptions
{
    private const HOST_LABEL_PATTERN = '[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?';

    public const OPTIONS_SHAPE = [
        'cantoName' => 'string',
        'cantoDomain' => 'string',
        'appId' => 'string',
        'appSecret' => 'string',
        'redirectUri' => 'string',
        'mdcDomainName' => 'string',
        'mdcAwsAccountId' => 'string',
        'masterImageSize' => 'int',
        'httpClient' => ClientInterface::class,
        'httpClientOptions' => [
            'debug' => 'bool',
            'timeout' => 'int',
            'userAgent' => 'string',
        ],
        'logger' => LoggerInterface::class,
    ];

    private string $cantoName;

    private string $cantoDomain;

    private string $appId;

    private string $appSecret;

    private string $redirectUri;

    private string $mdcDomainName;

    private string $mdcAwsAccountId;

    private int $masterImageSize;

    private ?ClientInterface $httpClient;

    private array $httpClientOptions;

    private ?LoggerInterface $logger;

    /**
     * @throws \InvalidArgumentException
     */
    #[ArrayShape(self::OPTIONS_SHAPE)]
    public function __construct(array $options = [])
    {
        $this->validateOptions($options);

        $this->cantoName = $options['cantoName'];
        $this->cantoDomain = $options['cantoDomain'] ?? 'canto.com';
        $this->appId = $options['appId'];
        $this->appSecret = $options['appSecret'];
        $this->redirectUri = $options['redirectUri'] ?? '';
        $this->httpClient = $options['httpClient'] ?? null;
        $this->httpClientOptions = array_replace(
            [
                'debug' => false,
                'timeout' => 10,
                'userAgent' => 'Canto PHP API client',
            ],
            $options['httpClientOptions'] ?? []
        );
        $this->logger = $options['logger'] ?? null;
        $this->mdcAwsAccountId = $options['mdcAwsAccountId'] ?? '';
        $this->mdcDomainName = $options['mdcDomainName'] ?? '';
        $this->masterImageSize = $options['masterImageSize'] ?? 2048;
    }

    public function getCantoName(): string
    {
        return $this->cantoName;
    }

    public function getCantoDomain(): string
    {
        return $this->cantoDomain;
    }

    public function getAppId(): string
    {
        return $this->appId;
    }

    public function getAppSecret(): string
    {
        return $this->appSecret;
    }

    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    public function getHttpClient(): ?ClientInterface
    {
        return $this->httpClient;
    }

    public function getHttpClientOptions(): array
    {
        return $this->httpClientOptions;
    }

    public function getMdcDomainName(): string
    {
        return $this->mdcDomainName;
    }

    public function getMdcAwsAccountId(): string
    {
        return $this->mdcAwsAccountId;
    }

    public function getMasterImageSize(): int
    {
        return $this->masterImageSize;
    }

    public function getLogger(): ?LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @throws \InvalidArgumentException
     */
    protected function validateOptions(array $options): void
    {
        if (is_string($options['cantoName'] ?? null) === false || $options['cantoName'] === '') {
            throw new \InvalidArgumentException(
                'Option cantoName is not given or not a string.',
                1626849038
            );
        }
        // cantoName, cantoDomain and mdcDomainName become the host of request urls, anything
        // but a plain host name could redirect requests including credentials to another host
        if (!$this->isHostName($options['cantoName'], false)) {
            throw new \InvalidArgumentException(
                'Option cantoName must be a single host name label.',
                1793361001
            );
        }
        if (isset($options['cantoDomain']) && (!is_string($options['cantoDomain']) || !$this->isHostName($options['cantoDomain'], true))) {
            throw new \InvalidArgumentException(
                'Option cantoDomain must be a host name.',
                1793361002
            );
        }
        if (($options['mdcDomainName'] ?? '') !== '' && (!is_string($options['mdcDomainName']) || !$this->isHostName($options['mdcDomainName'], true))) {
            throw new \InvalidArgumentException(
                'Option mdcDomainName must be a host name.',
                1793361003
            );
        }
        if (is_string($options['appId'] ?? null) === false || $options['appId'] === '') {
            throw new \InvalidArgumentException(
                'Option appId is not given or not a string.',
                1626849132
            );
        }
        if (is_string($options['appSecret'] ?? null) === false || $options['appSecret'] === '') {
            throw new \InvalidArgumentException(
                'Option appSecret is not given or not a string.',
                1626849145
            );
        }
    }

    private function isHostName(string $value, bool $allowMultipleLabels): bool
    {
        $label = self::HOST_LABEL_PATTERN;
        $pattern = $allowMultipleLabels ? '/^' . $label . '(?:\\.' . $label . ')+$/i' : '/^' . $label . '$/i';
        return strlen($value) <= 253 && preg_match($pattern, $value) === 1;
    }
}
