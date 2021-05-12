# Google-api-client
PHP package to manage google-api interactions

## Supports:
* Google Drive API
* Google Spreadsheet API

##Installation

```composer log
composer require obrio-team/google-api-client
```

## Config:
* Provide auth credential configs for native google-apiclient library
* Enable dependency injection. E.g. Symfony config.yaml
```yaml
services:
    ObrioTeam\GoogleApiClient\:
        resource: '../vendor/obrio-team/google-api-client/src'
```

* If you would like to define your custom ItemStatusService` statuses inject delimiter and your custom status extensions. E.g. Symfony config.yaml

```yaml
services:
  ObrioTeam\GoogleApiClient\Service\ItemStatus\GoogleItemStatusService:
    class: ObrioTeam\GoogleApiClient\Service\ItemStatus\GoogleItemStatusService
    arguments:
      - ':' #delimiter
      - [
          '@App\Domain\Statuses\CustomStatusOne' #implementation
      ]
```
## Google drive service

| Method        | Arguments | Output  |  Description  |
| ------------- |:-------------:|:-----:|:--------------------------------------------------------|
| getFile      | string $fileId, array $optionalParam = [] | ?Google_Service_Drive_DriveFile  |  Get single Google drive file by its ID or null.  |
| createFile      | Google_Service_Drive_DriveFile $file, array $optionalParams = [] | ?Google_Service_Drive_DriveFile |  Try to create file with locally created DriveFile object  |
| updateFile      | string $fileId, Google_Service_Drive_DriveFile $file, array $optionalParams = [] | ?Google_Service_Drive_DriveFile |  Try to update file with locally created DriveFile object  |
| getFilteredFileList| ?string $folderId = null, bool $deepPagination = false, ?GoogleItemRuleStrategyInterface ...$itemRuleStrategies | array |  Get list of files. If set deepPagination=true all files through google pagination will be matched. Also you can perform filtering by various strategies  |
| exportFileContent| string $fileId, string $mimeType = 'text/plain', array $optionalParams = [] | \GuzzleHttp\Psr7\Request | Get content of GoogleDoc file in desired format by file ID |
| getNonGoogleDocsFileContent| string $fileId | \GuzzleHttp\Psr7\Request | Get contents of non-GoogleDoc file by file ID |
| changeFileStatus| Google_Service_Drive_DriveFile $file, ContentStatusAbstract $targetStatus | ?Google_Service_Drive_DriveFile | Try to update file name with status using GoogleItemStatusService |


## Google spreadsheet service

| Method        | Arguments | Output  |  Description  |
| ------------- |:-------------:|:-----:|:--------------------------------------------------------|
| getJustNewValues      | string $spreadsheetId, string $range = '' | GoogleSheetValuesResponse  |  Get only updated values as DTO with key=>value array |
| getSheets      | string $spreadsheetId | GoogleSheetSheetsResponse  |  Get DTO with list of spreadsheet sheets   |
| getValues      | string $spreadsheetId, ?string $sheetTitle = null, string $range = '' | GoogleSheetValuesResponse  |  Get sheet values as DTO with key=>value array. If no sheetTitle defined - getting values from first sheet in spreadsheet. Specific range can set.   |
| updateField      | string $spreadsheetId, UpdateFieldRequest $updateFieldRequest | Google_Service_Sheets_UpdateValuesResponse  |  Update single cell value   |
| updateRange      | string $spreadsheetId, UpdateRangeRequest $updateRangeRequest | Google_Service_Sheets_UpdateValuesResponse  |  Update range request in specified sheet.  |
| addSpreadsheetPage  | string $spreadsheetId, AddSpreadsheetPageRequest $addSpreadsheetPageRequest | Google_Service_Sheets_BatchUpdateSpreadsheetResponse  |  Add new sheet to the spreadsheet.  |
| appendDimensionToSpreadsheetPage  | string $spreadsheetId, AppendDimensionToSpreadsheetPageRequest $appendDimensionToSpreadsheetPageRequest | Google_Service_Sheets_BatchUpdateSpreadsheetResponse  |  Append colimns or rows to the specific sheet of the spreadsheet.  |
| createGoogleSpreadsheet  | string $name, ?string $parentFolderId = null | Google_Service_Sheets_Spreadsheet  |  Create new spreadsheet file in defined parent folder.  |

### Hints
To get all mentioned ...Request DTOs use this factories:
```phpt
\ObrioTeam\GoogleApiClient\Factory\GoogleDriveFileFactory::class;
\ObrioTeam\GoogleApiClient\Factory\GoogleSpreadsheetRequestFactory::class;
```
