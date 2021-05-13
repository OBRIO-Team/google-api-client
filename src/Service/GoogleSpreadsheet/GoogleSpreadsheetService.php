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

namespace ObrioTeam\GoogleApiClient\Service\GoogleSpreadsheet;

use Google_Service_Sheets_BatchUpdateSpreadsheetResponse;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_UpdateValuesResponse;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_AppendValuesResponse;
use ObrioTeam\GoogleApiClient\Client\GoogleServiceDriveLocal;
use ObrioTeam\GoogleApiClient\Client\GoogleServicesFactory;
use ObrioTeam\GoogleApiClient\Client\GoogleServiceSpreadsheetLocal;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AddSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AppendDimensionToSpreadsheetPageRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\AppendSingleRowRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateFieldRequest;
use ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet\UpdateRangeRequest;
use ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet\GoogleSheetSheetsResponse;
use ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet\GoogleSheetValuesResponse;
use ObrioTeam\GoogleApiClient\Factory\GoogleDriveFileFactory;

/**
 * Class GoogleSpreadsheet
 * @package ObrioTeam\GoogleApiClient\Service\GoogleSpreadsheetService
 */
class GoogleSpreadsheetService
{
    private GoogleServiceSpreadsheetLocal $googleSpreadsheetClient;
    private GoogleServiceDriveLocal $googleServiceDriveLocal;
    private GoogleDriveFileFactory $googleDriveFileFactory;

    /**
     * GoogleSpreadsheet constructor.
     * @param GoogleServicesFactory $googleServicesFactory
     * @param GoogleDriveFileFactory $googleDriveFileFactory
     */
    public function __construct(
        GoogleServicesFactory $googleServicesFactory,
        GoogleDriveFileFactory $googleDriveFileFactory
    ) {
        $this->googleSpreadsheetClient = $googleServicesFactory->createGoogleServiceSheet();
        $this->googleServiceDriveLocal = $googleServicesFactory->createGoogleServiceDrive();
        $this->googleDriveFileFactory = $googleDriveFileFactory;
    }

    /**
     * @param string $spreadsheetId
     * @param string $range
     * @return GoogleSheetValuesResponse
     */
    public function getJustNewValues(string $spreadsheetId, string $range = ''): GoogleSheetValuesResponse
    {
        $response = $this->getValues($spreadsheetId, $range);
        $response->setValues(array_filter($response->getValues(), function (array $value) {
            return !isset($value['added']);
        }));

        return $response;
    }

    /**
     * @param string $spreadsheetId
     * @return GoogleSheetSheetsResponse
     */
    public function getSheets(string $spreadsheetId): GoogleSheetSheetsResponse
    {
        return new GoogleSheetSheetsResponse($this->googleSpreadsheetClient->getSheets($spreadsheetId));
    }

    /**
     * @param string $spreadsheetId
     * @param string|null $sheetTitle
     * @param string $range
     * @return GoogleSheetValuesResponse
     */
    public function getValues(
        string $spreadsheetId,
        ?string $sheetTitle = null,
        string $range = ''
    ): GoogleSheetValuesResponse {
        return $this->serializeResponseValues($this->googleSpreadsheetClient->getValues($spreadsheetId, $sheetTitle,
            $range));
    }

    /**
     * @param string $spreadsheetId
     * @param UpdateFieldRequest $updateFieldRequest
     * @return Google_Service_Sheets_UpdateValuesResponse
     */
    public function updateField(
        string $spreadsheetId,
        UpdateFieldRequest $updateFieldRequest
    ): Google_Service_Sheets_UpdateValuesResponse {
        return $this->googleSpreadsheetClient->updateField($spreadsheetId, $updateFieldRequest);
    }

    /**
     * @param string $spreadsheetId
     * @param UpdateRangeRequest $updateRangeRequest
     * @return Google_Service_Sheets_UpdateValuesResponse
     */
    public function updateRange(
        string $spreadsheetId,
        UpdateRangeRequest $updateRangeRequest
    ): Google_Service_Sheets_UpdateValuesResponse {
        return $this->googleSpreadsheetClient->updateRange($spreadsheetId, $updateRangeRequest);
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
        return $this->googleSpreadsheetClient->addSpreadsheetPage($spreadsheetId, $addSpreadsheetPageRequest);
    }

    /**
     * @param string $spreadsheetId
     * @param AppendDimensionToSpreadsheetPageRequest $appendDimensionToSpreadsheetPageRequest
     * Use to add columns/rows and avoid potential grid limits on inserts
     * @return Google_Service_Sheets_BatchUpdateSpreadsheetResponse
     */
    public function appendDimensionToSpreadsheetPage(
        string $spreadsheetId,
        AppendDimensionToSpreadsheetPageRequest $appendDimensionToSpreadsheetPageRequest
    ): Google_Service_Sheets_BatchUpdateSpreadsheetResponse {
        return $this->googleSpreadsheetClient->appendDimensionToSpreadsheetPage($spreadsheetId,
            $appendDimensionToSpreadsheetPageRequest);
    }

    /**
     * @param string $name
     * @param string|null $parentFolderId
     * @return Google_Service_Sheets_Spreadsheet
     */
    public function createGoogleSpreadsheet(
        string $name,
        ?string $parentFolderId = null
    ): Google_Service_Sheets_Spreadsheet {
        $file = $this->googleDriveFileFactory->createSpreadsheetFile($name, $parentFolderId);
        $createdFile = $this->googleServiceDriveLocal->createFile($file);

        return $this->googleSpreadsheetClient->getSpreadsheet($createdFile->getId());
    }

    /**
     * @param string $spreadsheetId
     * @param AppendSingleRowRequest $appendSingleRowRequest
     * @return Google_Service_Sheets_AppendValuesResponse
     */
    public function appendSingleRow(
        string $spreadsheetId,
        AppendSingleRowRequest $appendSingleRowRequest
    ): Google_Service_Sheets_AppendValuesResponse {
        $updateRange = $appendSingleRowRequest->getSheetPageTitle() . '!' . chr($appendSingleRowRequest->getColumnStart() + 65) . '1';


        if (is_array(current($appendSingleRowRequest->getValues()))) {
            $finalValues = [...$appendSingleRowRequest->getValues()];
        } else {
            $finalValues = [
                'values' => $appendSingleRowRequest->getValues(),
            ];
        }

        return $this->googleSpreadsheetClient->spreadsheets_values->append(
            $spreadsheetId,
            $updateRange,
            new Google_Service_Sheets_ValueRange([
                'range' => $updateRange,
                'values' => $finalValues,
            ]),
            [
                'valueInputOption' => 'USER_ENTERED',
                'insertDataOption' => 'INSERT_ROWS'
            ]
        );
    }

    /**
     * @param array $googleSheetRows
     * @return GoogleSheetValuesResponse
     */
    private function serializeResponseValues(array $googleSheetRows): GoogleSheetValuesResponse
    {
        $response = [];
        $headerRow = current($googleSheetRows);
        $headerFields = $this->getHeaderFields($headerRow);
        $positionOfAddedBlock = $headerFields['added'] ?? count($headerRow);

        while ($bodyRow = next($googleSheetRows)) {
            $rowId = key($googleSheetRows) + 1;//in the google sheet position start from 1 not from 0
            $response[$rowId] = $this->serializeOneRow($headerFields, $bodyRow);
        }

        return new GoogleSheetValuesResponse($positionOfAddedBlock, $response);
    }

    /**
     * @param array $headerColumns
     * @return array
     */
    private function getHeaderFields(array $headerColumns): array
    {
        $headerFields = [];
        foreach ($headerColumns as $key => $value) {
            $value = trim($value);
            $headerFields[$value] = $key;
        }

        return $headerFields;
    }


    /**
     * @param array $headerFields
     * @param array $value
     * @return array
     */
    private function serializeOneRow(array $headerFields, array $value): array
    {
        $data = [];
        foreach ($headerFields as $key => $columnId) {
            if (isset($value[$columnId])) {
                $data = array_merge_recursive($data, $this->addFiled(explode('.', $key), $value[$columnId]));
            }
        }

        return $data;
    }

    /**
     * @param array $nameOfParams
     * @param $value
     * @return array
     */
    private function addFiled(array $nameOfParams, $value): array
    {
        $name = array_shift($nameOfParams);
        $data[$name] = !empty($nameOfParams) ? $this->addFiled($nameOfParams, $value) : $value;

        return $data;
    }

}