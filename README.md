# PHP client for Canto API

## Fork information

This package is a fork of [fairway/canto-saas-api](https://packagist.org/packages/fairway/canto-saas-api).

It was forked to be able to extend and maintain a public version of [Canto FAL TYPO3 extension](https://github.com/TYPO3-Canto/canto-fal).

## Example usage

```php
use TYPO3Canto\CantoApi\ClientOptions;
use TYPO3Canto\CantoApi\Client;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetTreeRequest;

$clientOptions = new ClientOptions([
    'cantoName' => 'my-canto-name',
    'cantoDomain' => 'canto.de',
    'appId' => '123456789',
    'appSecret' => 'my-app-secret',
]);
$client = new Client($clientOptions);
$accessToken = $client->authorizeWithClientCredentials('my-user@email.com')
                      ->getAccessToken();
$client->setAccessToken($accessToken);
$allFolders = $client->libraryTree()
                     ->getTree(new GetTreeRequest())
                     ->getResults();
```
