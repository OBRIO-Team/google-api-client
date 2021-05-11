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

namespace ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet;

/**
 * Class GoogleSheetValuesResponse
 * @package ObrioTeam\GoogleApiClient\DTO\Response\Spreadsheet
 */
class GoogleSheetValuesResponse
{
    private int $addedPosition;
    private array $values;

    /**
     * GoogleSheetValuesResponse constructor.
     * @param int $addedPosition
     * @param array $values
     */
    public function __construct(int $addedPosition, array $values)
    {
        $this->addedPosition = $addedPosition;
        $this->values = $values;
    }


    /**
     * @return int
     */
    public function getAddedPosition(): int
    {
        return $this->addedPosition;
    }

    /**
     * @param array $value
     */
    public function setValues(array $value)
    {
        $this->values = $value;
    }

    /**
     * @return array
     */
    public function getValues(): array
    {
        return $this->values;
    }

}
