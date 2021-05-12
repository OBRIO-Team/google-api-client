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

namespace ObrioTeam\GoogleApiClient\DTO\Request\Drive;

use Google_Service_Drive_DriveFile;

/**
 * Class GoogleDriveFileWithMediaDTO
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Drive
 */
class GoogleDriveFileWithMediaDTO
{
    private Google_Service_Drive_DriveFile $file;
    private array $additionalOptions;

    /**
     * GoogleDriveFileWithMediaDTO constructor.
     * @param Google_Service_Drive_DriveFile $file
     * @param array $additionalOptions
     */
    public function __construct(Google_Service_Drive_DriveFile $file, array $additionalOptions)
    {
        $this->file = $file;
        $this->additionalOptions = $additionalOptions;
    }

    /**
     * @return Google_Service_Drive_DriveFile
     */
    public function getFile(): Google_Service_Drive_DriveFile
    {
        return $this->file;
    }

    /**
     * @return array
     */
    public function getAdditionalOptions(): array
    {
        return $this->additionalOptions;
    }
}