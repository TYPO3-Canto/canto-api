<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Tests;

use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use TYPO3Canto\CantoApi\ClientOptions;

class ClientOptionsTest extends TestCase
{
    /**
     * @test
     */
    public function useDefaultValues(): void
    {
        $clientOptions = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
        ]);

        self::assertSame('canto.com', $clientOptions->getCantoDomain());
        self::assertSame('', $clientOptions->getRedirectUri());
        self::assertNull($clientOptions->getHttpClient());
        self::assertNull($clientOptions->getLogger());
        self::assertFalse($clientOptions->getHttpClientOptions()['debug']);
        self::assertSame(10, $clientOptions->getHttpClientOptions()['timeout']);
        self::assertSame('Canto PHP API client', $clientOptions->getHttpClientOptions()['userAgent']);
    }

    /**
     * @test
     */
    public function setCantoName(): void
    {
        $options = new ClientOptions([
            'appId' => 'no-empty',
            'appSecret' => 'no-empty',
            'cantoName' => 'my-name',
        ]);
        self::assertSame('my-name', $options->getCantoName());
    }

    /**
     * @test
     */
    public function setCantoDomain(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'cantoDomain' => 'canto.global',
        ]);
        self::assertSame('canto.global', $options->getCantoDomain());
    }

    /**
     * @test
     */
    public function setAppId(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appSecret' => 'not-empty',
            'appId' => '12345-Abc',
        ]);
        self::assertSame('12345-Abc', $options->getAppId());
    }

    /**
     * @test
     */
    public function setAppSecret(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => '12345-Abc',
        ]);
        self::assertSame('12345-Abc', $options->getAppSecret());
    }

    /**
     * @test
     */
    public function setRedirectUri(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'redirectUri' => 'http:/localhost',
        ]);
        self::assertSame('http:/localhost', $options->getRedirectUri());
    }

    /**
     * @test
     */
    public function setHttpClient(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'httpClient' => new Client(),
        ]);
        self::assertInstanceOf(Client::class, $options->getHttpClient());
    }

    /**
     * @test
     */
    public function setLogger(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'logger' => new NullLogger(),
        ]);
        self::assertInstanceOf(NullLogger::class, $options->getLogger());
    }

    /**
     * @test
     */
    public function setHttpOptions(): void
    {
        $options = new ClientOptions([
            'cantoName' => 'not-empty',
            'appId' => 'not-empty',
            'appSecret' => 'not-empty',
            'httpClientOptions' => [
                'debug' => true,
                'timeout' => 30,
                'userAgent' => 'My api client',
            ],
        ]);
        self::assertTrue($options->getHttpClientOptions()['debug']);
        self::assertSame(30, $options->getHttpClientOptions()['timeout']);
        self::assertSame('My api client', $options->getHttpClientOptions()['userAgent']);
    }

    /**
     * @return array<string, array{array<string, string>, int}>
     */
    public static function invalidHostOptionsDataProvider(): array
    {
        return [
            'cantoName with path' => [['cantoName' => 'evil.tld/x?'], 1793361001],
            'cantoName with dot' => [['cantoName' => 'evil.tld'], 1793361001],
            'cantoName with user info' => [['cantoName' => 'user@evil'], 1793361001],
            'cantoName with space' => [['cantoName' => 'My name'], 1793361001],
            'cantoDomain with fragment' => [['cantoName' => 'test', 'cantoDomain' => 'evil.tld#'], 1793361002],
            'cantoDomain with port' => [['cantoName' => 'test', 'cantoDomain' => 'evil.tld:8080'], 1793361002],
            'cantoDomain single label' => [['cantoName' => 'test', 'cantoDomain' => 'localhost'], 1793361002],
            'mdcDomainName with path' => [['cantoName' => 'test', 'mdcDomainName' => 'cdn.tld/evil'], 1793361003],
        ];
    }

    /**
     * @test
     * @dataProvider invalidHostOptionsDataProvider
     * @param array<string, string> $hostOptions
     */
    public function invalidHostOptionsAreRejected(array $hostOptions, int $expectedCode): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionCode($expectedCode);

        new ClientOptions(array_merge(['appId' => 'no-empty', 'appSecret' => 'no-empty'], $hostOptions));
    }

    /**
     * @test
     */
    public function validHostOptionsAreAccepted(): void
    {
        $options = new ClientOptions([
            'appId' => 'no-empty',
            'appSecret' => 'no-empty',
            'cantoName' => 'my-tenant',
            'cantoDomain' => 'ca.canto.com',
            'mdcDomainName' => 'd1234.cloudfront.net',
        ]);

        self::assertSame('ca.canto.com', $options->getCantoDomain());
        self::assertSame('d1234.cloudfront.net', $options->getMdcDomainName());
    }
}
