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

namespace ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter;


use ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter\RuleStrategy\GoogleItemRuleStrategyInterface;

/**
 * Class GoogleItemRuleFilterService
 * @package ObrioTeam\GoogleApiClient\Service\GoogleItemRuleFilter
 */
class GoogleItemRuleFilterService
{
    /**
     * @param GoogleItemRuleStrategyInterface $itemRuleStrategy
     * @return bool
     */
    public function isSatisfy(GoogleItemRuleStrategyInterface $itemRuleStrategy): bool
    {
        return $itemRuleStrategy->isCompliesRule();
    }
}
