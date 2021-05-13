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

use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AddSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AppendDimensionToSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AppendSingleRowRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateFieldRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateRangeRequest;
use ObrioTeam\GoogleApiClient\SpreadsheetDataModel\SpreadsheetDataModel;

/**
 * Class GoogleSpreadsheetRequestFactory
 * @package ObrioTeam\GoogleApiClient\Factory
 */
class GoogleSpreadsheetRequestFactory
{
    const FIRST_ROW = 1;

    const DIMENSION_ROWS = 'ROWS',
        DIMENSION_COLUMNS = 'COLUMNS';

    const DIMENSIONS = [
        self::DIMENSION_ROWS,
        self::DIMENSION_COLUMNS,
    ];

    /**
     * @param SpreadsheetDataModel $spreadsheetDataModel
     * @param int $contentCount
     * @param float|null $red
     * @param float|null $green
     * @param float|null $blue
     * @return AddSpreadsheetPageRequest
     */
    public function createAddSpreadsheetPageRequest(
        SpreadsheetDataModel $spreadsheetDataModel,
        int $contentCount,
        ?float $red = null,
        ?float $green = null,
        ?float $blue = null
    ): AddSpreadsheetPageRequest {
        return new AddSpreadsheetPageRequest(
            $spreadsheetDataModel->getSheetTitle(),
            ($contentCount < 100) ? 100 : $contentCount,
            count($spreadsheetDataModel->getHeaders()),
            $red,
            $green,
            $blue
        );
    }

    /**
     * @param int $sheetId
     * @param string $dimension
     * @param int $countOfNewDimensions
     * @return AppendDimensionToSpreadsheetPageRequest
     */
    public function createAppendDimensionToSpreadsheetPageRequest(
        int $sheetId,
        string $dimension,
        int $countOfNewDimensions
    ): AppendDimensionToSpreadsheetPageRequest {
        return new AppendDimensionToSpreadsheetPageRequest(
            $sheetId,
            (in_array($dimension, self::DIMENSIONS)) ? $dimension : self::DIMENSION_ROWS,
            $countOfNewDimensions
        );
    }

    /**
     * @param int $columnId
     * @param int $rowId
     * @param string $value
     * @return UpdateFieldRequest
     */
    public function createUpdateFieldRequest(int $columnId, int $rowId, string $value): UpdateFieldRequest
    {
        return new UpdateFieldRequest($columnId, $rowId, $value);
    }

    /**
     * @param array $values
     * @param SpreadsheetDataModel $spreadsheetDataModel
     * @param int|null $rowStart
     * @return UpdateRangeRequest
     */
    public function createUpdateRangeRequest(
        array $values,
        SpreadsheetDataModel $spreadsheetDataModel,
        ?int $rowStart = null
    ): UpdateRangeRequest {
        $rowRangeValues = [];
        $i = $rowStart ?? self::FIRST_ROW;
        foreach ($values as $value) {

            $singleRow = [];
            foreach ($spreadsheetDataModel->getHeaders() as $target) {
                if ($spreadsheetDataModel->getColumnValueMutationCallback($target) !== null) {
                    $singleRow[] = $spreadsheetDataModel->getColumnValueMutationCallback($target)($value[$target]);
                } else {
                    $singleRow[] = $value[$target];
                }
            }

            $rowRangeValues[] = $singleRow;
            $i++;
        }

        return new UpdateRangeRequest(
            $rowStart ?? self::FIRST_ROW,
            $spreadsheetDataModel->getFirstColumnPosition(),
            $i,
            $spreadsheetDataModel->getLastColumnPosition(),
            $rowRangeValues,
            $spreadsheetDataModel->getSheetTitle()
        );
    }

    /**
     * @param array $values
     * @param SpreadsheetDataModel $spreadsheetDataModel
     * @return AppendSingleRowRequest
     */
    public function createAppendSingleRowRequest(
        array $values,
        SpreadsheetDataModel $spreadsheetDataModel
    ): AppendSingleRowRequest {
        $targetHeaders = $spreadsheetDataModel->getHeaders();

        $plainValues = $this->getFlatRowValuesByHeaders($values, $targetHeaders);

        return new AppendSingleRowRequest(
            $spreadsheetDataModel->getFirstColumnPosition(),
            $plainValues,
            $spreadsheetDataModel->getSheetTitle()
        );
    }

    /**
     * @param array $rawValues
     * @param array $targetHeaders
     * @return array
     */
    private function getFlatRowValuesByHeaders(array $rawValues, array $targetHeaders): array
    {
        $plainValues = [];

        if (is_array(current($rawValues))) {
            foreach ($rawValues as $rawValue) {
                $plainValues[] = $this->getFlatRowValuesByHeaders($rawValue, $targetHeaders);
            }
        } else {
            foreach ($targetHeaders as $targetHeader) {
                $plainValues[] = $rawValues[$targetHeader];
            }
        }

        return $plainValues;
    }
}