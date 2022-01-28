<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Common;

use HBS\SacEnhancer\Formatter\FormatterInterface;

class EmptyFormatter implements FormatterInterface
{
    public function format($response, $queryArgs = [])
    {
        return $response;
    }
}
