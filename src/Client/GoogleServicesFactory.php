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

use Google_Service;
use InvalidArgumentException;


/**
 * Class GoogleServicesFactory
 * @package ObrioTeam\GoogleApiClient\Client
 */
class GoogleServicesFactory
{
    const
        GOOGLE_SHEET = 'google_sheet',
        GOOGLE_DRIVE = 'google_drive';

    const GOOGLE_SERVICES = [
        self::GOOGLE_DRIVE => GoogleServiceDriveLocal::class,
        self::GOOGLE_SHEET => GoogleServiceSpreadsheetLocal::class,
    ];

    /**
     * @var GoogleClientLocal
     */
    private GoogleClientLocal $googleClientLocal;

    public function __construct(GoogleClientLocal $googleClientLocal)
    {
        $this->googleClientLocal = $googleClientLocal;
    }

    /**
     * @param string $serviceName
     * @return Google_Service
     */
    public function createGoogleService(string $serviceName): Google_Service
    {
        if (!array_key_exists($serviceName, self::GOOGLE_SERVICES)) {
            throw new InvalidArgumentException("The google service $serviceName is not supported or not registered on local factory!");
        }
        $service = self::GOOGLE_SERVICES[$serviceName];

        return new $service($this->googleClientLocal->getClient());
    }

    /**
     * @return GoogleServiceDriveLocal
     */
    public function createGoogleServiceDrive(): GoogleServiceDriveLocal
    {
        return $this->createGoogleService(self::GOOGLE_DRIVE);
    }

    /**
     * @return GoogleServiceSpreadsheetLocal
     */
    public function createGoogleServiceSheet(): GoogleServiceSpreadsheetLocal
    {
        return $this->createGoogleService(self::GOOGLE_SHEET);
    }
}
