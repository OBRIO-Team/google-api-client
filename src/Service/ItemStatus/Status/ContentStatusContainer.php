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


use RuntimeException;

/**
 * Class ContentStatusContainer
 * @package ObrioTeam\GoogleApiClient\Service\ItemStatusService\Status
 */
class ContentStatusContainer
{
    private ContentStatusAbstract $currentStatus;

    /**
     * ContentStatus constructor.
     * @param ContentStatusAbstract $currentStatus
     */
    public function __construct(ContentStatusAbstract $currentStatus)
    {
        $this->currentStatus = $currentStatus;
    }

    /**
     * @param ContentStatusAbstract $newStatus
     * @return ContentStatusAbstract
     */
    public function transitionTo(ContentStatusAbstract $newStatus): ContentStatusAbstract
    {
        if($this->currentStatus->canBeChangedTo($newStatus)) {
            $this->currentStatus = $newStatus;
        } else {
            throw new RuntimeException(sprintf(
                "Can't change status from '%s' to '%s' - transition not allowed in %1\$s",
                $this->currentStatus->getStatusLabel(),
                $newStatus->getStatusLabel()
            ));
        }

        return $this->currentStatus;
    }

    /**
     * @return ContentStatusAbstract
     */
    public function getCurrentStatus(): ContentStatusAbstract
    {
        return $this->currentStatus;
    }
}
