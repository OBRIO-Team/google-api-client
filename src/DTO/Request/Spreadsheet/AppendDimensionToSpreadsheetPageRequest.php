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

namespace ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet;

/**
 * Class AppendDimensionToSpreadsheetPageRequest
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet
 */
class AppendDimensionToSpreadsheetPageRequest
{
    private int $sheetId;
    private string $dimension;
    private int $length;

    /**
     * AppendDimensionToSpreadsheetPageRequest constructor.
     * @param int $sheetId
     * @param string $dimension
     * @param int $length
     */
    public function __construct(int $sheetId, string $dimension, int $length)
    {
        $this->sheetId = $sheetId;
        $this->dimension = $dimension;
        $this->length = $length;
    }

    /**
     * @return int
     */
    public function getSheetId(): int
    {
        return $this->sheetId;
    }

    /**
     * @return string
     */
    public function getDimension(): string
    {
        return $this->dimension;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }
}
