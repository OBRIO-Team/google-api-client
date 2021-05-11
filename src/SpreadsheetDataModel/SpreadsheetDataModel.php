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

namespace ObrioTeam\GoogleApiClient\SpreadsheetDataModel;

/**
 * Interface SpreadsheetDataModel
 * @package ObrioTeam\GoogleApiClient\SpreadsheetDataModel
 */
interface SpreadsheetDataModel
{
    /**
     * @return string
     */
    public function getSheetTitle(): string;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return int
     */
    public function getFirstColumnPosition(): int;

    /**
     * @return int
     */
    public function getLastColumnPosition(): int;

    /**
     * @param string $columnHeader
     * @return callable|null
     * If needed to mutate column value return callable function
     * e.g.
     *  function ($columnValue) {
     *       return trim($columnValue);
     *  }
     */
    public function getColumnValueMutationCallback(string $columnHeader): ?callable;
}