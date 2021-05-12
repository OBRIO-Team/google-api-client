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

namespace ObrioTeam\GoogleApiClient\Factory;

use Google_Service_Drive_DriveFile;
use ObrioTeam\GoogleApiClient\DTO\Request\Drive\GoogleDriveFileWithMediaDTO;

/**
 * Class GoogleDriveFileFactory
 * @package ObrioTeam\GoogleApiClient\Factory
 */
class GoogleDriveFileFactory
{
    const
        MIME_SPREADSHEET = 'application/vnd.google-apps.spreadsheet',
        MIME_IMAGE_PNG = 'image/png',
        MIME_IMAGE_JPEG = 'image/jpeg';

    public function createSpreadsheetFile(string $name, ?string $parentFolderId = null): Google_Service_Drive_DriveFile
    {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($name);
        $file->setMimeType(self::MIME_SPREADSHEET);
        $file->setParents([$parentFolderId]);

        return $file;
    }

    public function createImageFileWithContent(
        string $name,
        string $data,
        string $mimeType = self::MIME_IMAGE_PNG,
        ?string $parentFolderId = null
    ): GoogleDriveFileWIthMediaDTO {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($name);
        $file->setParents([$parentFolderId]);

        return new GoogleDriveFileWithMediaDTO(
            $file,
            [
                'data' => $data,
                'mimeType' => $mimeType,
                'uploadType' => 'multipart'
            ]
        );
    }
}