<?php
/**
 * This file is part of the obrio-team/google-api-client library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Obrio team: https://www.linkedin.com/company/obrio-genesis
 *
 * @author  Alexander Zodov <alekzo1992@gmail.com>
 * @copyright Copyright (c)2021
 * @license http://opensource.org/licenses/MIT MIT
 *
 */

declare(strict_types=1);

namespace ObrioTeam\GoogleApiClient\Service\GoogleDrive;

use Google\Service\Exception;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_FileList;
use GuzzleHttp\Psr7\Request;
use ObrioTeam\GoogleApiClient\Client\GoogleServiceDriveLocal;
use ObrioTeam\GoogleApiClient\Client\GoogleServicesFactory;
use ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\GoogleItemRuleFilterService;
use ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\RuleStrategy\GoogleItemRuleStrategyInterface;
use ObrioTeam\GoogleApiClient\Service\ItemStatus\GoogleItemStatusService;
use ObrioTeam\GoogleApiClient\Service\ItemStatus\Status\ContentStatusAbstract;

/**
 * Class GoogleDriveService
 * @package ObrioTeam\GoogleApiClient\Service\GoogleDrive
 */
class GoogleDriveService
{
    private GoogleItemRuleFilterService $googleItemRuleFilterService;
    private GoogleServiceDriveLocal $googleDriveClient;
    private GoogleItemStatusService $googleItemStatusService;

    /**
     * GoogleDriveService constructor.
     * @param GoogleServicesFactory $googleServicesFactory
     * @param GoogleItemRuleFilterService $googleItemRuleFilterService
     * @param GoogleItemStatusService $googleItemStatusService
     */
    public function __construct(
        GoogleServicesFactory $googleServicesFactory,
        GoogleItemRuleFilterService $googleItemRuleFilterService,
        GoogleItemStatusService $googleItemStatusService
    ) {
        $this->googleItemRuleFilterService = $googleItemRuleFilterService;
        $this->googleItemStatusService = $googleItemStatusService;
        $this->googleDriveClient = $googleServicesFactory->createGoogleServiceDrive();
    }

    /**
     * @param string $fileId
     * @param array $optionalParam
     * @return Google_Service_Drive_DriveFile|null
     */
    public function getFile(string $fileId, array $optionalParam = []): ?Google_Service_Drive_DriveFile
    {
        try {
            $file = $this->googleDriveClient->getFile($fileId, $optionalParam);
        } catch (Exception $exception) {
            $file = null;
        }

        return $file;
    }

    /**
     * @param Google_Service_Drive_DriveFile $file
     * @param array $optionalParams
     * @return Google_Service_Drive_DriveFile|null
     */
    public function createFile(
        Google_Service_Drive_DriveFile $file,
        array $optionalParams = []
    ): ?Google_Service_Drive_DriveFile {
        try {
            $file = $this->googleDriveClient->createFile($file, $optionalParams);
        } catch (Exception $exception) {
            $file = null;
        }

        return $file;
    }

    /**
     * @param string $fileId
     * @param Google_Service_Drive_DriveFile $file
     * @param array $optionalParams
     * @return Google_Service_Drive_DriveFile|null
     */
    public function updateFile(
        string $fileId,
        Google_Service_Drive_DriveFile $file,
        array $optionalParams = []
    ): ?Google_Service_Drive_DriveFile {
        try {
            $file = $this->googleDriveClient->updateFile($fileId, $file, $optionalParams);
        } catch (Exception $exception) {
            $file = null;
        }

        return $file;
    }

    /**
     * @param string|null $folderId
     * @param bool $deepPagination
     * @param GoogleItemRuleStrategyInterface|null ...$itemRuleStrategies
     * @return Google_Service_Drive_DriveFile[]
     */
    public function getFilteredFileList(
        ?string $folderId = null,
        bool $deepPagination = false,
        ?GoogleItemRuleStrategyInterface ...$itemRuleStrategies
    ): array {
        if ($deepPagination) {
            $fileList = $this->googleDriveClient->getFileListWithDeepPagination($folderId);
        } else {
            $fileList = $this->googleDriveClient->getFileList($folderId);
        }

        return $this->filterByRules($fileList, ...$itemRuleStrategies);
    }


    /**
     * @param string $fileId
     * @param string $mimeType
     * @param array $optionalParams
     * @return Request
     */
    public function exportFileContent(
        string $fileId,
        string $mimeType = 'text/plain',
        array $optionalParams = []
    ): Request {
        return $this->googleDriveClient->files->export($fileId, $mimeType, $optionalParams);
    }

    /**
     * @param string $fileId
     * @return Request
     */
    public function getNonGoogleDocsFileContent(string $fileId): Request
    {
        /**
         * @var Request $contentRequest
         * with this options native method return \GuzzleHttp\Psr7\Request
         */
        $contentRequest = $this->googleDriveClient->files->get($fileId, ['alt' => 'media']);
        return $contentRequest;
    }

    /**
     * @param Google_Service_Drive_DriveFile $file
     * @param ContentStatusAbstract $targetStatus
     * @return Google_Service_Drive_DriveFile|null
     */
    public function changeFileStatus(Google_Service_Drive_DriveFile $file, ContentStatusAbstract $targetStatus): ?Google_Service_Drive_DriveFile
    {
        $file = $this->googleItemStatusService->changeItemStatus($file, $targetStatus);
        return $this->updateFile($file->getId(), $file);
    }

    /**
     * @param Google_Service_Drive_FileList $fileList
     * @param GoogleItemRuleStrategyInterface|null ...$googleItemRuleStrategies
     * @return array
     */
    private function filterByRules(
        Google_Service_Drive_FileList $fileList,
        ?GoogleItemRuleStrategyInterface ...$googleItemRuleStrategies
    ): array {
        $filteredFileList = [];
        if (!empty($googleItemRuleStrategies)) {
            foreach ($fileList as $file) {
                if ($this->verifyRulesSetSatisfied($file, ...$googleItemRuleStrategies)) {
                    $filteredFileList[] = $file;
                }
            }
        } else {
            $filteredFileList = $fileList->getFiles();
        }

        return $filteredFileList;
    }

    /**
     * @param Google_Service_Drive_DriveFile $file
     * @param GoogleItemRuleStrategyInterface ...$googleItemRuleStrategies
     * @return bool
     */
    private function verifyRulesSetSatisfied(
        Google_Service_Drive_DriveFile $file,
        GoogleItemRuleStrategyInterface ...$googleItemRuleStrategies
    ): bool {
        $result = false;
        foreach ($googleItemRuleStrategies as $ruleStrategy) {
            $ruleStrategy->setVerifiableItem($file);
            $verify = $this->googleItemRuleFilterService->isSatisfy($ruleStrategy);

            if (!$verify) {
                return false;
            }

            $result = true;
        }

        return $result;
    }
}