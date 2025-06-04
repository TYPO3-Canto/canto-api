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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use TYPO3Canto\CantoApi\Endpoint\Asset;
use TYPO3Canto\CantoApi\Endpoint\Authorization\OAuth2;
use TYPO3Canto\CantoApi\Endpoint\LibraryTree;
use TYPO3Canto\CantoApi\Endpoint\Upload;
use TYPO3Canto\CantoApi\Helper\MdcUrlHelper;
use TYPO3Canto\CantoApi\Http\Authorization\OAuth2Request;
use TYPO3Canto\CantoApi\Http\Authorization\OAuth2Response;

class Client
{
    protected const API_VERSION = 'v1';
    protected const API_ROUTE = 'https://%s.%s/api';
    protected const MDC_ROUTE = 'https://%s/image/%s/%s';

    protected ClientOptions $options;

    protected LoggerInterface $logger;

    protected ClientInterface $httpClient;

    protected ?string $accessToken = null;

    public function __construct(ClientOptions $options)
    {
        $this->options = $options;
        $this->httpClient = $this->options->getHttpClient() ?? $this->buildHttpClient();
        $this->logger = $this->options->getLogger() ?? new NullLogger();
    }

    public function getOptions(): ClientOptions
    {
        return $this->options;
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getHttpClient(): ClientInterface
    {
        return $this->httpClient;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): void
    {
        if ($accessToken !== '') {
            $this->accessToken = $accessToken;
        }
    }

    /**
     * @throws Endpoint\Authorization\AuthorizationFailedException|Endpoint\Authorization\NotAuthorizedException
     */
    public function authorizeWithClientCredentials(string $userId = '', string $scope = OAuth2Request::SCOPE_ADMIN): OAuth2Response
    {
        $request = new OAuth2Request();
        $request->setAppId($this->options->getAppId())
            ->setAppSecret($this->options->getAppSecret())
            ->setRedirectUri($this->options->getRedirectUri())
            ->setScope($scope);
        if ($userId !== '') {
            $request->setUserId($userId);
        }

        $OAuth2 = new OAuth2($this);
        $response = $OAuth2->obtainAccessToken($request);
        $this->setAccessToken($response->getAccessToken());

        return $response;
    }

    public function asset(): Asset
    {
        return new Asset($this);
    }

    public function libraryTree(): LibraryTree
    {
        return new LibraryTree($this);
    }

    public function upload(): Upload
    {
        return new Upload($this);
    }

    public function mdc(): MdcUrlHelper
    {
        return new MdcUrlHelper($this);
    }

    public function getApiUrl(string $path = null): string
    {
        $url = sprintf(
            self::API_ROUTE,
            $this->getOptions()->getCantoName(),
            $this->getOptions()->getCantoDomain(),
        );
        return sprintf(
            '%s/%s/%s',
            $url,
            self::API_VERSION,
            $path ?? ''
        );
    }

    public function getMdcUrl(string $assetId = '', string $scheme = 'image'): string
    {
        $path = '';
        if ($assetId) {
            $path = sprintf('%s_%s/', $scheme, $assetId);
        }
        return sprintf(
            self::MDC_ROUTE,
            $this->options->getMdcDomainName(),
            $this->options->getMdcAwsAccountId(),
            $path,
        );
    }

    protected function buildHttpClient(): ClientInterface
    {
        return new \GuzzleHttp\Client([
            'allow_redirects' => true,
            'connect_timeout' => (int)$this->options->getHttpClientOptions()['timeout'],
            'debug' => (bool)$this->options->getHttpClientOptions()['debug'],
            'headers' => [
                'userAgent' => $this->options->getHttpClientOptions()['userAgent'],
            ],
        ]);
    }
}
