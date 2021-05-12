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
 * Class UpdateFieldRequest
 * @package ObrioTeam\GoogleApiClient\DTO\Request\Spreadsheet
 */
class UpdateFieldRequest
{
    private int $columnId;
    private int $rowId;
    private string $value;

    /**
     * UpdateFieldRequest constructor.
     * @param int $columnId
     * @param int $rowId
     * @param string $value
     */
    public function __construct(int $columnId, int $rowId, string  $value)
    {
        $this->columnId = $columnId;
        $this->rowId = $rowId;
        $this->value = $value;
    }

    /**
     * @return int
     */
    public function getColumnId(): int
    {
        return $this->columnId;
    }

    /**
     * @return int
     */
    public function getRowId(): int
    {
        return $this->rowId;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
