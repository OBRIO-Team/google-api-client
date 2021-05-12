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

use ObrioTeam\GoogleApiClient\Service\ItemStatus\FileNameParserService;
use Google_Service_Drive_DriveFile;


/**
 * Class GoogleItemIsInStatusRuleStrategy
 * @package ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\RuleStrategy
 */
class GoogleItemIsInStatusRuleStrategy implements GoogleItemRuleStrategyInterface
{
    private Google_Service_Drive_DriveFile $file;
    private FileNameParserService $fileNameParserService;
    private string $targetStatusLabel;
    private string $delimiter;


    /**
     * GoogleItemIsInStatusRuleStrategy constructor.
     * @param string $targetStatusLabel
     * @param string $delimiter
     */
    public function __construct(string $targetStatusLabel, string $delimiter = ':')
    {
        $this->fileNameParserService = new FileNameParserService();
        $this->targetStatusLabel = $targetStatusLabel;
        $this->delimiter = $delimiter;
    }


    /**
     * @return bool
     */
    public function isCompliesRule(): bool
    {
        $chunkedName = $this->fileNameParserService->makeChunkedName($this->file->getName(), $this->delimiter);
        return $chunkedName[0] === $this->targetStatusLabel;
    }

    /**
     * @param Google_Service_Drive_DriveFile $item
     */
    public function setVerifiableItem($item): void
    {
        $this->file = $item;
    }
}
