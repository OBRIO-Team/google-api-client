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

namespace ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\RuleStrategy;

use Google_Service_Drive_DriveFile;


/**
 * Class GoogleItemIsSpreadsheetRuleStrategy
 * @package ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\RuleStrategy
 */
class GoogleItemIsSpreadsheetRuleStrategy implements GoogleItemRuleStrategyInterface
{
    private Google_Service_Drive_DriveFile $file;

    /**
     * @return bool
     */
    public function isCompliesRule(): bool
    {
        return 'application/vnd.google-apps.spreadsheet' === $this->file->getMimeType();
    }

    /**
     * @param Google_Service_Drive_DriveFile $item
     */
    public function setVerifiableItem($item): void
    {
        $this->file = $item;
    }
}
