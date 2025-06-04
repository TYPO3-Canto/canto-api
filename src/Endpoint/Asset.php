<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Endpoint;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use TYPO3Canto\CantoApi\Http\Asset\AddKeywordsRequest;
use TYPO3Canto\CantoApi\Http\Asset\AddVersionCommentRequest;
use TYPO3Canto\CantoApi\Http\Asset\AssignContentToAlbumRequest;
use TYPO3Canto\CantoApi\Http\Asset\AttachKeywordToContentRequest;
use TYPO3Canto\CantoApi\Http\Asset\AttachTagToContentRequest;
use TYPO3Canto\CantoApi\Http\Asset\BatchGetContentDetailsRequest;
use TYPO3Canto\CantoApi\Http\Asset\BatchGetContentDetailsResponse;
use TYPO3Canto\CantoApi\Http\Asset\BatchUpdatePropertiesRequest;
use TYPO3Canto\CantoApi\Http\Asset\BatchUpdatePropertiesResponse;
use TYPO3Canto\CantoApi\Http\Asset\CreateShareLinksRequest;
use TYPO3Canto\CantoApi\Http\Asset\CreateShareLinksResponse;
use TYPO3Canto\CantoApi\Http\Asset\GetContentDetailsRequest;
use TYPO3Canto\CantoApi\Http\Asset\GetContentDetailsResponse;
use TYPO3Canto\CantoApi\Http\Asset\ListSpecificSchemeRequest;
use TYPO3Canto\CantoApi\Http\Asset\RemoveContentFromAlbumRequest;
use TYPO3Canto\CantoApi\Http\Asset\RemoveKeywordToContentRequest;
use TYPO3Canto\CantoApi\Http\Asset\RemoveTagFromContentRequest;
use TYPO3Canto\CantoApi\Http\Asset\RenameContentRequest;
use TYPO3Canto\CantoApi\Http\Asset\SearchRequest;
use TYPO3Canto\CantoApi\Http\Asset\SearchResponse;
use TYPO3Canto\CantoApi\Http\Asset\SuccessResponse;
use TYPO3Canto\CantoApi\Http\EmptyResponse;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\RequestInterface;

final class Asset extends AbstractEndpoint
{
    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function search(SearchRequest $request): SearchResponse
    {
        $response = $this->getResponse($request);
        return new SearchResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function listTheContentOfSpecifiedScheme(ListSpecificSchemeRequest $request): SearchResponse
    {
        $response = $this->getResponse($request);
        return new SearchResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchUpdateProperties(BatchUpdatePropertiesRequest $request): BatchUpdatePropertiesResponse
    {
        $response = $this->getResponse($request);
        return new BatchUpdatePropertiesResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchGetContentDetail(BatchGetContentDetailsRequest $request): BatchGetContentDetailsResponse
    {
        $response = $this->getResponse($request);
        return new BatchGetContentDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function getContentDetails(GetContentDetailsRequest $request): GetContentDetailsResponse
    {
        $response = $this->getResponse($request);
        return new GetContentDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function getAuthorizedUrlContent(string $uri, string $method = RequestInterface::GET): ResponseInterface
    {
        $httpRequest = new Request($method, $uri);
        return $this->getResponseWithHttpRequest($httpRequest);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function addVersionComment(AddVersionCommentRequest $request): SuccessResponse
    {
        $response = $this->getResponse($request);
        return new SuccessResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function renameContent(RenameContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function attachTagToContent(AttachTagToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeTagFromContent(RemoveTagFromContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function addKeywords(AddKeywordsRequest $request): SuccessResponse
    {
        $response = $this->getResponse($request);
        return new SuccessResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function attachKeywordToContent(AttachKeywordToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function assignContentToAlbum(AssignContentToAlbumRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeKeywordFromContent(RemoveKeywordToContentRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function batchDeleteContent($request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function removeContentsFromAlbum(RemoveContentFromAlbumRequest $request): EmptyResponse
    {
        $response = $this->getResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function downloadToPath(string $sourcePath, string $destinationPath): void
    {
        $fileContentReadStream = $this
            ->getAuthorizedUrlContent($sourcePath)
            ->getBody()
            ->detach();
        $tempFileWriteStream = fopen($destinationPath, 'wb');
        stream_copy_to_stream($fileContentReadStream, $tempFileWriteStream);
        fclose($tempFileWriteStream);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function createShareLinks(CreateShareLinksRequest $request): CreateShareLinksResponse
    {
        $response = $this->getResponse($request);
        return new CreateShareLinksResponse($response);
    }
}
