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

use Google_Client;
use Google_Service_Drive;
use Google_Service_Sheets;


/**
 * Class GoogleClientLocal
 * @package ObrioTeam\GoogleApiClient\Client
 */
class GoogleClientLocal
{
    private Google_Client $client;

    /**
     * GoogleSheetClient constructor.
     * @param Google_Client $googleClient
     */
    public function __construct(Google_Client $googleClient)
    {
        $this->client = $googleClient;
        $this->client->setScopes([
            Google_Service_Drive::DRIVE_METADATA,
            Google_Service_Drive::DRIVE_APPDATA,
            Google_Service_Drive::DRIVE,
            Google_Service_Drive::DRIVE_FILE,
            Google_Service_Sheets::SPREADSHEETS,
        ]);
        $this->client->useApplicationDefaultCredentials();
    }

    /**
     * @return Google_Client
     */
    public function getClient(): Google_Client
    {
        return $this->client;
    }
}
