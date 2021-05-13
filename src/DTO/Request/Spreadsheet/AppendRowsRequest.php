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
 * Class AppendRowsRequest
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet
 */
class AppendRowsRequest
{
    private int $columnStart;
    private array $values;
    private ?string $sheetPageTitle;

    /**
     * AppendSingleRowRequest constructor.
     * @param int $columnStart
     * @param array $values
     * @param string|null $sheetPageTitle
     */
    public function __construct(int $columnStart, array $values, ?string $sheetPageTitle = '')
    {
        $this->columnStart = $columnStart;
        $this->values = $values;
        $this->sheetPageTitle = $sheetPageTitle;
    }

    /**
     * @return int
     */
    public function getColumnStart(): int
    {
        return $this->columnStart;
    }

    /**
     * @param int $columnStart
     */
    public function setColumnStart(int $columnStart): void
    {
        $this->columnStart = $columnStart;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        $this->values = $values;
    }

    /**
     * @return string|null
     */
    public function getSheetPageTitle(): ?string
    {
        return $this->sheetPageTitle;
    }

    /**
     * @param string|null $sheetPageTitle
     */
    public function setSheetPageTitle(?string $sheetPageTitle): void
    {
        $this->sheetPageTitle = $sheetPageTitle;
    }
}
