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


use InvalidArgumentException;

/**
 * Class ContentStatusAbstract
 * @package ObrioTeam\GoogleApiClient\Service\ItemStatusService\Status
 */
abstract class ContentStatusAbstract
{
    const
        STATUS_READY = 'ready',
        STATUS_IN_PROCESS = 'in_progress',
        STATUS_SUCCESS = 'success',
        STATUS_FAIL = 'fail';

    const STATUSES = [
        self::STATUS_READY => ContentStatusReady::class,
        self::STATUS_IN_PROCESS => ContentStatusInProcess::class,
        self::STATUS_SUCCESS => ContentStatusSuccess::class,
        self::STATUS_FAIL => ContentStatusFail::class,
    ];

    /**
     * @return string
     */
    abstract function getStatusLabel(): string;

    /**
     * @param string $statusLabel
     * @param array $customStatuses
     * @return ContentStatusAbstract
     */
    public static function create(string $statusLabel, array $customStatuses = []): ContentStatusAbstract
    {
        $resultStatuses = array_merge(self::STATUSES, $customStatuses);
        if (!in_array($statusLabel, $resultStatuses)) {
            throw new InvalidArgumentException(sprintf('Unknown google file status given - %s', $statusLabel));
        }
        $statusesClassName = $resultStatuses[$statusLabel];
        return new $statusesClassName();
    }

    /**
     * @param ContentStatusAbstract $newStatus
     * @return bool
     */
    public function canBeChangedTo(ContentStatusAbstract $newStatus): bool
    {
        return true;
    }
}
