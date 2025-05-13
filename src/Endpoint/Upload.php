<?php

declare(strict_types=1);

/*
 * This file is part of the "fairway_canto_saas_api" library by eCentral GmbH.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

namespace TYPO3Canto\CantoApi\Endpoint;

use TYPO3Canto\CantoApi\Http\EmptyResponse;
use TYPO3Canto\CantoApi\Http\InvalidResponseException;
use TYPO3Canto\CantoApi\Http\Upload\GetUploadSettingRequest;
use TYPO3Canto\CantoApi\Http\Upload\GetUploadSettingResponse;
use TYPO3Canto\CantoApi\Http\Upload\QueryUploadStatusRequest;
use TYPO3Canto\CantoApi\Http\Upload\QueryUploadStatusResponse;
use TYPO3Canto\CantoApi\Http\Upload\UploadFileRequest;

final class Upload extends AbstractEndpoint
{
    /**
     * Before uploading a file, you need to retrieve this setting.
     * Note: An upload setting is valid for 5 hours. You will need to retrieve settings again after 5 hours to continue use.
     * The resulting settings vary between .de and non-.de-environments depending on the used canto-domain.
     * @throws InvalidResponseException|Authorization\NotAuthorizedException
     * @see Upload::uploadFile()
     */
    public function getUploadSetting(GetUploadSettingRequest $request, bool $forceDeEnvironment = false): GetUploadSettingResponse
    {
        $response = $this->getResponse($request);
        $isDeEnvironment = $forceDeEnvironment || $this->getClient()->getOptions()->getCantoDomain() === 'canto.de';
        return new GetUploadSettingResponse($response, $isDeEnvironment);
    }

    /**
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     * @throws \JsonException
     */
    public function uploadFile(UploadFileRequest $request): EmptyResponse
    {
        $response = $this->getMultipartResponse($request);
        return new EmptyResponse($response);
    }

    /**
     * Query upload status for recently uploaded files.
     * @throws InvalidResponseException
     * @throws Authorization\NotAuthorizedException
     */
    public function queryUploadStatus(QueryUploadStatusRequest $request): QueryUploadStatusResponse
    {
        $response = $this->getResponse($request);
        return new QueryUploadStatusResponse($response);
    }
}
