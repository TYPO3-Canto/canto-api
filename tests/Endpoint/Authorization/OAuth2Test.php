<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Endpoint\Authorization;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use TYPO3Canto\CantoApi\Client;
use TYPO3Canto\CantoApi\ClientOptions;
use TYPO3Canto\CantoApi\Endpoint\Authorization\AuthorizationFailedException;
use TYPO3Canto\CantoApi\Endpoint\Authorization\OAuth2;
use TYPO3Canto\CantoApi\Http\Authorization\OAuth2Request;

class OAuth2Test extends TestCase
{
    /**
     * @test
     */
    public function successfulObtainAccessToken(): void
    {
        $mockHandler = new MockHandler([
            new Response(
                200,
                [],
                '{"accessToken":"access-token-1234","expiresIn":3600,"tokenType":"Bearer","refreshToken":"refresh-token-1234"}'
            ),
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $oAuth2 = new OAuth2($clientMock);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);
        $response = $oAuth2->obtainAccessToken($oAuthRequest);

        self::assertSame('access-token-1234', $response->getAccessToken());
        self::assertSame(3600, $response->getExpiresIn());
        self::assertSame('Bearer', $response->getTokenType());
        self::assertSame('refresh-token-1234', $response->getRefreshToken());
    }

    /**
     * @test
     */
    public function obtainAccessTokenWithInvalidHttpResponseStatusCode(): void
    {
        $this->expectExceptionCode(1626447895);

        $mockHandler = new MockHandler([
            new ClientException(
                'Error Communicating with Server',
                new Request('POST', 'test'),
                new Response(400)
            ),
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $oAuth2 = new OAuth2($clientMock);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);
        $oAuth2->obtainAccessToken($oAuthRequest);
    }

    /**
     * @test
     */
    public function credentialsAreSentInBodyNotInUrl(): void
    {
        $history = [];
        $mockHandler = new MockHandler([
            new Response(200, [], '{"accessToken":"a","expiresIn":3600,"tokenType":"Bearer","refreshToken":"r"}'),
        ]);
        $clientMock = $this->buildClientMock($mockHandler, $history);
        assert($clientMock instanceof Client);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);

        (new OAuth2($clientMock))->obtainAccessToken($oAuthRequest);

        $sentRequest = $history[0]['request'];
        self::assertSame('', $sentRequest->getUri()->getQuery());
        self::assertSame('application/x-www-form-urlencoded', $sentRequest->getHeaderLine('Content-Type'));
        parse_str((string)$sentRequest->getBody(), $body);
        self::assertSame('app-secret-1234', $body['app_secret']);
        self::assertSame('refresh-token-1234', $body['refresh_token']);
    }

    /**
     * @test
     */
    public function credentialsAreMaskedInExceptionMessage(): void
    {
        $mockHandler = new MockHandler([
            new ClientException(
                'Failed: app_secret=app-secret-1234&code=code-1234 {"refresh_token":"refresh-token-1234"}',
                new Request('POST', 'test'),
                new Response(400)
            ),
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);
        $oAuthRequest = $this->buildRequestMock();
        assert($oAuthRequest instanceof OAuth2Request);

        try {
            (new OAuth2($clientMock))->obtainAccessToken($oAuthRequest);
            self::fail('AuthorizationFailedException expected');
        } catch (AuthorizationFailedException $e) {
            self::assertStringNotContainsString('app-secret-1234', $e->getMessage());
            self::assertStringNotContainsString('code-1234', $e->getMessage());
            self::assertStringNotContainsString('refresh-token-1234', $e->getMessage());
            self::assertStringContainsString('app_secret=***', $e->getMessage());
        }
    }

    /**
     * @param array<int, array<string, mixed>>|null $history
     */
    protected function buildClientMock(MockHandler $mockHandler, ?array &$history = null): MockObject
    {
        $optionsMock = $this->getMockBuilder(ClientOptions::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getCantoName', 'getCantoDomain'])
            ->getMock();
        $optionsMock->method('getCantoName')->willReturn('test');
        $optionsMock->method('getCantoDomain')->willReturn('canto.com');

        $handlerStack = HandlerStack::create($mockHandler);
        if ($history !== null) {
            $handlerStack->push(Middleware::history($history));
        }
        $httpClient = new HttpClient([
            'handler' => $handlerStack,
        ]);

        $clientMock = $this->getMockBuilder(Client::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getHttpClient', 'getLogger', 'getOptions', 'getAccessToken'])
            ->getMock();
        $clientMock->method('getHttpClient')->willReturn($httpClient);
        $clientMock->method('getLogger')->willReturn(new NullLogger());
        $clientMock->method('getOptions')->willReturn($optionsMock);
        $clientMock->method('getAccessToken')->willReturn(null);

        return $clientMock;
    }

    protected function buildRequestMock(): MockObject
    {
        $requestMock = $this->getMockBuilder(OAuth2Request::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getFormParams'])
            ->getMock();
        $requestMock->method('getFormParams')->willReturn([
            'app_id' => 'app-id-1234',
            'app_secret' => 'app-secret-1234',
            'grant_type' => 'client_credentials',
            'redirect_uri' => 'http://localhost',
            'code' => 'code-1234',
            'refresh_token' => 'refresh-token-1234',
            'scope' => 'admin',
            'user_id' => 'user@example.tld',
        ]);

        return $requestMock;
    }
}
