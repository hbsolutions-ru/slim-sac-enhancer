<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Utility;

use InvalidArgumentException;
use Fig\Http\Message\StatusCodeInterface;

final class HttpStatusUtility
{
    /**
     * @param int $status
     * @return int
     * @see https://github.com/slimphp/Slim-Psr7/blob/1.5/src/Response.php
     */
    public static function filterStatus(int $status): int
    {
        if ($status < StatusCodeInterface::STATUS_CONTINUE || $status > 599) {
            throw new InvalidArgumentException('Invalid HTTP status code.');
        }

        return $status;
    }
}
