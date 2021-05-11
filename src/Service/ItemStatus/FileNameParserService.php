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

namespace ObrioTeam\GoogleApiClient\Service\ItemStatus;


/**
 * Class FileNameParserService
 * @package ObrioTeam\GoogleApiClient\Service\ItemStatus
 */
class FileNameParserService
{
    /**
     * @param string $fileName
     * @param string $delimiter
     * @return mixed
     */
    public function determinateCurrentFileStatus(string $fileName, string $delimiter)
    {
        return $this->makeChunkedName($fileName, $delimiter)[0];
    }

    /**
     * @param string $fileName
     * @param string $delimiter
     * @return array
     */
    public function makeChunkedName(string $fileName, string $delimiter): array
    {
        $chunks = explode($delimiter, $fileName);
        array_walk($chunks, 'trim');
        return $chunks;
    }
}
