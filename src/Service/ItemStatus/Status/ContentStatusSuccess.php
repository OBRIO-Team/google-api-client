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

namespace ObrioTeam\GoogleApiClient\Service\ItemStatus\Status;


/**
 * Class ContentStatusSuccess
 * @package ObrioTeam\GoogleApiClient\Service\ItemStatusService\Status
 */
class ContentStatusSuccess extends ContentStatusAbstract
{
    public function getStatusLabel(): string
    {
        return self::STATUS_SUCCESS;
    }

    /**
     * @param ContentStatusAbstract $newStatus
     * @return bool
     */
    public function canBeChangedTo(ContentStatusAbstract $newStatus): bool
    {
        return false; //default success status is endstate
    }
}
