<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests\Endpoint;

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
use TYPO3Canto\CantoApi\Endpoint\Asset;
use TYPO3Canto\CantoApi\Http\Asset\BatchUpdatePropertiesRequest;

class AssetTest extends TestCase
{
    /**
     * @test
     */
    public function batchUpdatePropertiesSuccessfulObtainResponse(): void
    {
        $mockHandler = new MockHandler([new Response(200, [], 'success')]);
        $clientMock = $this->buildClientMock($mockHandler);

        assert($clientMock instanceof Client);
        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $response = $assetEndpoint->batchUpdateProperties($request);

        self::assertSame('success', $response->getBody());
    }

    /**
     * @test
     */
    public function batchUpdatePropertiesExpectNotAuthorizedException(): void
    {
        $this->expectExceptionCode(1626717511);

        $mockHandler = new MockHandler([
            new ClientException(
                'Error Communicating with Server',
                new Request('PUT', 'test'),
                new Response(401)
            ),
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $assetEndpoint->batchUpdateProperties($request);
    }

    /**
     * @test
     */
    public function batchUpdatePropertiesExpectUnexpectedHttpStatusException(): void
    {
        $this->expectExceptionCode(1627649307);

        $mockHandler = new MockHandler([
            new ClientException(
                'Error Communicating with Server',
                new Request('PUT', 'test'),
                new Response(400, [], 'success')
            ),
        ]);
        $clientMock = $this->buildClientMock($mockHandler);
        assert($clientMock instanceof Client);

        $assetEndpoint = new Asset($clientMock);
        $request = $this->buildRequestMock();
        assert($request instanceof BatchUpdatePropertiesRequest);
        $assetEndpoint->batchUpdateProperties($request);
    }

    /**
     * @return array<string, array{string, bool}>
     */
    public static function authorizedUrlDataProvider(): array
    {
        return [
            'canto instance' => ['https://test.canto.com/direct/image/abc/original', true],
            'canto instance upper case' => ['https://TEST.canto.com/direct/image/abc/original', true],
            'canto instance explicit port' => ['https://test.canto.com:443/direct/image/abc/original', true],
            'foreign host' => ['https://evil.tld/direct/image/abc/original', false],
            'host suffix' => ['https://test.canto.com.evil.tld/x', false],
            'plain http' => ['http://test.canto.com/direct/image/abc/original', false],
            'other port' => ['https://test.canto.com:8443/x', false],
            'user info' => ['https://user@test.canto.com/x', false],
        ];
    }

    /**
     * @test
     * @dataProvider authorizedUrlDataProvider
     */
    public function accessTokenIsOnlySentToCantoInstance(string $uri, bool $expectToken): void
    {
        $history = [];
        $clientMock = $this->buildClientMock(new MockHandler([new Response(200, [], 'content')]), $history, 'access-token');
        assert($clientMock instanceof Client);

        (new Asset($clientMock))->getAuthorizedUrlContent($uri);

        self::assertSame(
            $expectToken ? 'Bearer access-token' : '',
            $history[0]['request']->getHeaderLine('Authorization')
        );
    }

    /**
     * @param array<int, array<string, mixed>>|null $history
     */
    protected function buildClientMock(MockHandler $mockHandler, ?array &$history = null, ?string $accessToken = null): MockObject
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
        $clientMock->method('getAccessToken')->willReturn($accessToken);

        return $clientMock;
    }

    protected function buildRequestMock(): MockObject
    {
        $requestMock = $this->getMockBuilder(BatchUpdatePropertiesRequest::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getBody'])
            ->getMock();
        $requestMock->method('getBody')->willReturn('{"contents":[],"properties":[]}');
        return $requestMock;
    }
}
