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

namespace ObrioTeam\GoogleApiClient\Client;

use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
use Google_Service_Drive_FileList;

/**
 * Class GoogleServiceDriveLocal
 * @package ObrioTeam\GoogleApiClient\Client
 */
class GoogleServiceDriveLocal extends Google_Service_Drive
{
    /**
     * @param string $folderId
     * @param string|null $nextPageToken
     * @return Google_Service_Drive_FileList
     */
    public function getFileList(string $folderId = '', ?string $nextPageToken = null): Google_Service_Drive_FileList
    {
        $files = $this->files;
        $params = [
            'fields' => 'nextPageToken, files(id, name, parents, fileExtension, mimeType, size, modifiedTime, createdTime, modifiedByMeTime, parents)',
            'pageSize' => 1000, //don`t trust google api docs - it is limited to 460 items
        ];

        if ($nextPageToken) {
            $params['pageToken'] = $nextPageToken;
        }

        if ($folderId) {
            $params['q'] = sprintf("'%s' in parents", $folderId);
        }

        return $files->listFiles($params);
    }

    /**
     * @param string $folderId
     * @return Google_Service_Drive_FileList
     */
    public function getFileListWithDeepPagination(string $folderId): Google_Service_Drive_FileList
    {
        $firstPage = $this->getFileList($folderId);
        $runnerPage = $firstPage;

        while (isset($runnerPage->nextPageToken)) {
            $tempPage = $this->getFileList($folderId, $runnerPage->nextPageToken);
            $firstPage->setFiles(
                array_merge($firstPage->getFiles(), $tempPage->getFiles())
            );
            $runnerPage = $tempPage;
        }

        return $firstPage;
    }

    /**
     * @param string $fileId
     * @param array $optionalParams
     * @return Google_Service_Drive_DriveFile
     */
    public function getFile(string $fileId, array $optionalParams = []): Google_Service_Drive_DriveFile
    {
        return $this->files->get($fileId, $optionalParams);
    }

    /**
     * @param Google_Service_Drive_DriveFile $file
     * @param array $optionalParams
     * @return Google_Service_Drive_DriveFile
     */
    public function createFile(Google_Service_Drive_DriveFile $file, array $optionalParams = []): Google_Service_Drive_DriveFile
    {
        return $this->files->create($file, $optionalParams);
    }

    /**
     * @param string $contentId
     * @param Google_Service_Drive_DriveFile $newContentObject
     * @param array $updateParams
     * @return Google_Service_Drive_DriveFile
     */
    public function updateFile(
        string $contentId,
        Google_Service_Drive_DriveFile $newContentObject,
        array $updateParams = []
    ): Google_Service_Drive_DriveFile {
        return $this->files->update($contentId, $newContentObject, $updateParams);
    }


}
