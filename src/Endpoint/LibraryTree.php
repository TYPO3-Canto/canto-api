<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Endpoint;

use TYPO3Canto\CantoApi\Endpoint\Authorization\NotAuthorizedException;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\LibraryTree\CreateAlbumFolderRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\CreateAlbumFolderResponse;
use TYPO3Canto\CantoApi\Http\LibraryTree\DeleteAlbumFolderResponse;
use TYPO3Canto\CantoApi\Http\LibraryTree\DeleteFolderOrAlbumRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetDetailsRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetDetailsResponse;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetMyCollectionDetailInfoRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetTreeRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\GetTreeResponse;
use TYPO3Canto\CantoApi\Http\LibraryTree\ListAlbumContentRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\ListAlbumContentResponse;
use TYPO3Canto\CantoApi\Http\LibraryTree\SearchFolderRequest;
use TYPO3Canto\CantoApi\Http\LibraryTree\SearchFolderResponse;

final class LibraryTree extends AbstractEndpoint
{
    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function searchFolderContent(SearchFolderRequest $request): SearchFolderResponse
    {
        $response = $this->getResponse($request);
        return new SearchFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function listAlbumContent(ListAlbumContentRequest $request): ListAlbumContentResponse
    {
        $response = $this->getResponse($request);
        return new ListAlbumContentResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getTree(GetTreeRequest $request): GetTreeResponse
    {
        $response = $this->getResponse($request);
        return new GetTreeResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getDetails(GetDetailsRequest $request): GetDetailsResponse
    {
        $response = $this->getResponse($request);
        return new GetDetailsResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function createFolder(CreateAlbumFolderRequest $request): CreateAlbumFolderResponse
    {
        $response = $this->getResponse($request);
        return new CreateAlbumFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function getMyCollectionDetailInfo(GetMyCollectionDetailInfoRequest $request): SearchFolderResponse
    {
        $response = $this->getResponse($request);
        return new SearchFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function createAlbum(CreateAlbumFolderRequest $request): CreateAlbumFolderResponse
    {
        $request->setType(CreateAlbumFolderRequest::ALBUM);
        $response = $this->getResponse($request);
        return new CreateAlbumFolderResponse($response);
    }

    /**
     * @throws InvalidResponseException
     * @throws NotAuthorizedException
     */
    public function deleteFolderOrAlbum(DeleteFolderOrAlbumRequest $request): DeleteAlbumFolderResponse
    {
        return new DeleteAlbumFolderResponse($this->getResponse($request));
    }
}
