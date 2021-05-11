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

namespace ObrioTeam\GoogleApiClient\Service\ItemStatus;

use Google_Service_Drive_DriveFile;
use ObrioTeam\GoogleApiClient\Service\ItemStatus\Status\ContentStatusAbstract;
use ObrioTeam\GoogleApiClient\Service\ItemStatus\Status\ContentStatusContainer;


/**
 * Class GoogleItemStatusService
 * @package ObrioTeam\GoogleApiClient\Service\ItemStatus
 */
class GoogleItemStatusService
{
    private string $delimiter;
    private array $customStatuses;
    private FileNameParserService $fileNameParserService;


    /**
     * GoogleItemStatusService constructor.
     * @param string $delimiter
     * @param array $customStatuses
     */
    public function __construct(string $delimiter = ':', array $customStatuses = [])
    {
        $this->fileNameParserService = new FileNameParserService();
        $this->delimiter = $delimiter;
        $this->customStatuses = $customStatuses;
    }

    /**
     * @param Google_Service_Drive_DriveFile $googleFile
     * @param ContentStatusAbstract $targetStatus
     * @return Google_Service_Drive_DriveFile
     */
    public function changeItemStatus(
        Google_Service_Drive_DriveFile $googleFile,
        ContentStatusAbstract $targetStatus
    ): Google_Service_Drive_DriveFile {
        $currentStatusLabel = $this->fileNameParserService->determinateCurrentFileStatus(
            $googleFile->getName(),
            $this->delimiter
        );
        $contentStatusContainer = new ContentStatusContainer(
            ContentStatusAbstract::create(
                $currentStatusLabel,
                $this->customStatuses
            )
        );

        return $this->changeStatus($googleFile, $contentStatusContainer, $targetStatus);
    }

    /**
     * @param Google_Service_Drive_DriveFile $googleFile
     * @param ContentStatusContainer $contentStatusContainer
     * @param ContentStatusAbstract $newStatus
     * @return Google_Service_Drive_DriveFile
     */
    private function changeStatus(
        Google_Service_Drive_DriveFile $googleFile,
        ContentStatusContainer $contentStatusContainer,
        ContentStatusAbstract $newStatus
    ): Google_Service_Drive_DriveFile {
        $newStatus = $contentStatusContainer->transitionTo($newStatus);
        $newFileName = sprintf('%s%s%s', $newStatus->getStatusLabel(), $this->delimiter, $googleFile->getName());
        $googleFile->setName($newFileName);

        return $googleFile;
    }
}
