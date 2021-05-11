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

use Google_Service_Sheets_BatchUpdateSpreadsheetResponse;
use Google_Service_Sheets_UpdateValuesResponse;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AddSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AppendDimensionToSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateFieldRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateRangeRequest;
use Google_Service_Sheets;
use Google_Service_Sheets_BatchUpdateSpreadsheetRequest;
use Google_Service_Sheets_Sheet;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_SpreadsheetProperties;
use Google_Service_Sheets_ValueRange;


/**
 * Class GoogleServiceSpreadsheetLocal
 * @package ObrioTeam\GoogleApiClient\Client
 */
class GoogleServiceSpreadsheetLocal extends Google_Service_Sheets
{
    /**
     * @param string $spreadsheetId
     * @return Google_Service_Sheets_Spreadsheet
     */
    public function getSpreadsheet(string $spreadsheetId): Google_Service_Sheets_Spreadsheet
    {
        return $this->spreadsheets->get($spreadsheetId);
    }

    /**
     * @param string $spreadsheetId
     * @return Google_Service_Sheets_Sheet[]|null
     */
    public function getSheets(string $spreadsheetId): ?array
    {
        $response = $this->spreadsheets->get($spreadsheetId);
        return $response->getSheets();
    }

    /**
     * @param string $spreadsheetId
     * @param string|null $sheetTitle
     * @param string $range
     * @return array
     */
    public function getValues(string $spreadsheetId, ?string $sheetTitle = null, string $range = ''): ?array
    {
        $range = $range ?: $this->determinateFullRange($spreadsheetId, $sheetTitle);
        if ($sheetTitle) {
            $range = $sheetTitle . '!' . $range;
        }
        $response = $this->spreadsheets_values->get($spreadsheetId, $range);
        return $response->getValues();
    }

    /**
     * @param string $spreadsheetId
     * @param UpdateFieldRequest $updateFieldRequest
     * @return Google_Service_Sheets_UpdateValuesResponse
     */

    public function updateField(string $spreadsheetId, UpdateFieldRequest $updateFieldRequest): Google_Service_Sheets_UpdateValuesResponse
    {
        $updateRange = $this->getCharIdentityByPosition($updateFieldRequest->getColumnId()) . $updateFieldRequest->getRowId();
        return $this->spreadsheets_values->update(
            $spreadsheetId,
            $updateRange,
            new Google_Service_Sheets_ValueRange([
                'range' => $updateRange,
                'majorDimension' => 'ROWS',
                'values' => ['values' => $updateFieldRequest->getValue()],
            ]),
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    /**
     * @param string $spreadsheetId
     * @param string|null $sheetTitle
     * @return string
     */
    public function determinateFullRange(string $spreadsheetId, ?string $sheetTitle = null): string
    {
        $charRange = $this->determinateCharRange($spreadsheetId, $sheetTitle);
        $rowsRange = $this->determinateRowRange($spreadsheetId, $sheetTitle);
        return "A1:" . $charRange . $rowsRange;
    }

    /**
     * @param string $spreadsheetId
     * @param string|null $sheetTitle
     * @return string
     */
    public function determinateCharRange(string $spreadsheetId, ?string $sheetTitle): string
    {
        $googleSheetPosition = 1;
        $nextCharNum = 0;
        $lastCharNum = 25;

        $firstChar = $this->getCharIdentityByPosition($nextCharNum); // A
        $lastChar = $this->getCharIdentityByPosition($lastCharNum);  // Z
        $charRange = $firstChar . $googleSheetPosition . ":" . $lastChar . $googleSheetPosition;

        $lastCurrentChar = 'A';
        $charRangeData = $this->getValues($spreadsheetId, $sheetTitle, $charRange);
        if (count($charRangeData) > 0 && count($charRangeData[0]) > 0) {
            foreach ($charRangeData[0] as $key => $dataCell) {
                if ($dataCell !== null) {
                    $lastCurrentChar = $this->getCharIdentityByPosition($key);
                    continue;
                }
                break;
            }
        }
        return $lastCurrentChar;
    }

    /**
     * @param string $spreadsheetId
     * @param string|null $sheetTitle
     * @return int
     */
    public function determinateRowRange(string $spreadsheetId, ?string $sheetTitle): int
    {
        $lastCurrentRow = 1;
        $maxIteration = 20;
        $stepPosition = 250;

        $offsetPosition = 1;
        $limitPosition = $stepPosition;
        while ($maxIteration > 0) {
            --$maxIteration;
            $char = "A";
            $charRange = $char . $offsetPosition . ":" . $char . $limitPosition;
            $charRangeData = $this->getValues($spreadsheetId, $sheetTitle, $charRange);

            if (count($charRangeData) > 0) {
                if (count($charRangeData) == $stepPosition) {
                    $offsetPosition = $limitPosition + 1;
                    $limitPosition = $limitPosition + $stepPosition;
                    continue;
                } else {
                    $lastCurrentRow = count($charRangeData) + ($offsetPosition - 1);
                    break;
                }
            } else {
                break;
            }
        }

        return $lastCurrentRow;
    }

    public function createSpreadsheet(string $name): Google_Service_Sheets_Spreadsheet
    {
        $request = new Google_Service_Sheets_Spreadsheet();
        $props = new Google_Service_Sheets_SpreadsheetProperties();
        $props->setTitle($name);
        $request->setProperties($props);
        return $this->spreadsheets->create($request);
    }

    /**
     * @param int $position
     * @return string
     */
    private function getCharIdentityByPosition(int $position): string
    {
        return chr($position + 65);
    }

    /**
     * @param string $spreadsheetId
     * @param UpdateRangeRequest $updateRangeRequest
     * @return Google_Service_Sheets_UpdateValuesResponse
     */
    public function updateRange(string $spreadsheetId, UpdateRangeRequest $updateRangeRequest): Google_Service_Sheets_UpdateValuesResponse
    {
        $updateRange = sprintf('%s:%s',
            $this->getCharIdentityByPosition($updateRangeRequest->getColumnStart()) . $updateRangeRequest->getRowStart(),
            $this->getCharIdentityByPosition($updateRangeRequest->getColumnEnd()) . $updateRangeRequest->getRowEnd()
        );

        if ($updateRangeRequest->getSheetPageTitle()) {
            $updateRange = sprintf('%s!%s', $updateRangeRequest->getSheetPageTitle(), $updateRange);
        }

        return $this->spreadsheets_values->update(
            $spreadsheetId,
            $updateRange,
            new Google_Service_Sheets_ValueRange([
                'range' => $updateRange,
                'majorDimension' => 'ROWS',
                'values' => $updateRangeRequest->getValues(),
            ]),
            ['valueInputOption' => 'USER_ENTERED']
        );
    }

    /**
     * @param string $spreadsheetId
     * @param AddSpreadsheetPageRequest $addSpreadsheetPageRequest
     * @return Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function addSpreadsheetPage(
        string $spreadsheetId,
        AddSpreadsheetPageRequest $addSpreadsheetPageRequest
    ): Google_Service_Sheets_BatchUpdateSpreadsheetResponse {
        return $this->spreadsheets->batchUpdate($spreadsheetId,
            new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
                [
                    'requests' => [
                        'addSheet' => [
                            'properties' => [
                                'title' => $addSpreadsheetPageRequest->getTitle(),
                                'gridProperties' => [
                                    'rowCount' => $addSpreadsheetPageRequest->getRowCount(),
                                    'columnCount' => $addSpreadsheetPageRequest->getColumnCount(),
                                ],
                                'tabColor' => [
                                    'red' => $addSpreadsheetPageRequest->getTabColorRed(),
                                    'green' => $addSpreadsheetPageRequest->getTabColorGreen(),
                                    'blue' => $addSpreadsheetPageRequest->getTabColorBlue(),
                                ],
                            ],
                        ],
                    ],
                ]
            )
        );
    }

    /**
     * @param string $spreadsheetId
     * @param AppendDimensionToSpreadsheetPageRequest $appendDimensionToSpreadsheetPageRequest
     * @return Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function appendDimensionToSpreadsheetPage(
        string $spreadsheetId,
        AppendDimensionToSpreadsheetPageRequest $appendDimensionToSpreadsheetPageRequest
    ): Google_Service_Sheets_BatchUpdateSpreadsheetResponse {
        return $this->spreadsheets->batchUpdate($spreadsheetId,
            new Google_Service_Sheets_BatchUpdateSpreadsheetRequest(
                [
                    'requests' => [
                        'appendDimension' => [
                            'sheetId' => $appendDimensionToSpreadsheetPageRequest->getSheetId(),
                            'dimension' => strtoupper($appendDimensionToSpreadsheetPageRequest->getDimension()),
                            'length' => $appendDimensionToSpreadsheetPageRequest->getLength(),
                        ],
                    ],
                ]
            )
        );
    }
}
