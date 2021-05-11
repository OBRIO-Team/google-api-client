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
 * Class UpdateRangeRequest
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet
 */
class UpdateRangeRequest
{
    private int $rowStart;
    private int $columnStart;
    private int $rowEnd;
    private int $columnEnd;
    private array $values;
    private ?string $sheetPageTitle;

    /**
     * UpdateRangeRequest constructor.
     * @param int $rowStart
     * @param int $columnStart
     * @param int $rowEnd
     * @param int $columnEnd
     * @param array $values
     * @param string|null $sheetPageTitle
     */
    public function __construct(
        int $rowStart,
        int $columnStart,
        int $rowEnd,
        int $columnEnd,
        array $values,
        ?string $sheetPageTitle = null
    ) {
        $this->rowStart = $rowStart;
        $this->columnStart = $columnStart;
        $this->rowEnd = $rowEnd;
        $this->columnEnd = $columnEnd;
        $this->values = $values;
        $this->sheetPageTitle = $sheetPageTitle;
    }

    /**
     * @return int
     */
    public function getRowStart(): int
    {
        return $this->rowStart;
    }

    /**
     * @param int $rowStart
     */
    public function setRowStart(int $rowStart): void
    {
        $this->rowStart = $rowStart;
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
     * @return int
     */
    public function getRowEnd(): int
    {
        return $this->rowEnd;
    }

    /**
     * @param int $rowEnd
     */
    public function setRowEnd(int $rowEnd): void
    {
        $this->rowEnd = $rowEnd;
    }

    /**
     * @return int
     */
    public function getColumnEnd(): int
    {
        return $this->columnEnd;
    }

    /**
     * @param int $columnEnd
     */
    public function setColumnEnd(int $columnEnd): void
    {
        $this->columnEnd = $columnEnd;
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

    public function addItemToValues(array $values, bool $tail = true): void
    {
        if($tail){
            $this->values = array_merge($this->values, $values);
        }else{
            array_unshift($this->values, $values);
        }
        $this->rowEnd = $this->rowEnd+1;
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
