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

namespace ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet;

/**
 * Class GoogleSheetSheetsResponse
 * @package ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet
 */
class GoogleSheetSheetsResponse
{
    private ?array $sheets;

    /**
     * GoogleSheetSheetsResponse constructor.
     * @param array|null $sheets
     */
    public function __construct(?array $sheets)
    {
        $this->sheets = $sheets;
    }

    /**
     * @return array|null
     */
    public function getSheets(): ?array
    {
        return $this->sheets;
    }

    /**
     * @param array|null $sheets
     */
    public function setSheets(?array $sheets): void
    {
        $this->sheets = $sheets;
    }




}
