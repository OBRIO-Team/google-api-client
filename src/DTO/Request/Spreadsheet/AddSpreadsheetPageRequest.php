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
 * Class AddSpreadsheetPageRequest
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet
 */
class AddSpreadsheetPageRequest
{
    private string $title;
    private int $rowCount;
    private int $columnCount;
    private float $tabColorRed;
    private float $tabColorGreen;
    private float $tabColorBlue;

    /**
     * AddSpreadsheetPageRequest constructor.
     * @param string $title
     * @param int $rowCount
     * @param int $columnCount
     * @param float $tabColorRed
     * @param float $tabColorGreen
     * @param float $tabColorBlue
     */
    public function __construct(
        string $title,
        int $rowCount,
        int $columnCount,
        float $tabColorRed = 1.0,
        float $tabColorGreen = 1.0,
        float $tabColorBlue = 1.0
    ) {
        $this->title = $title;
        $this->rowCount = $rowCount;
        $this->columnCount = $columnCount;
        $this->tabColorRed = $tabColorRed;
        $this->tabColorGreen = $tabColorGreen;
        $this->tabColorBlue = $tabColorBlue;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @param int $rowCount
     */
    public function setRowCount(int $rowCount): void
    {
        $this->rowCount = $rowCount;
    }

    /**
     * @return int
     */
    public function getColumnCount(): int
    {
        return $this->columnCount;
    }

    /**
     * @param int $columnCount
     */
    public function setColumnCount(int $columnCount): void
    {
        $this->columnCount = $columnCount;
    }

    /**
     * @return float
     */
    public function getTabColorRed(): float
    {
        return $this->tabColorRed;
    }

    /**
     * @param float $tabColorRed
     */
    public function setTabColorRed(float $tabColorRed): void
    {
        $this->tabColorRed = $tabColorRed;
    }

    /**
     * @return float
     */
    public function getTabColorGreen(): float
    {
        return $this->tabColorGreen;
    }

    /**
     * @param float $tabColorGreen
     */
    public function setTabColorGreen(float $tabColorGreen): void
    {
        $this->tabColorGreen = $tabColorGreen;
    }

    /**
     * @return float
     */
    public function getTabColorBlue(): float
    {
        return $this->tabColorBlue;
    }

    /**
     * @param float $tabColorBlue
     */
    public function setTabColorBlue(float $tabColorBlue): void
    {
        $this->tabColorBlue = $tabColorBlue;
    }

}
