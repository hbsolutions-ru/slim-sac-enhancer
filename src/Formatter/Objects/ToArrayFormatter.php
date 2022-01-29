<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Objects;

use HBS\Helpers\ObjectHelper;
use HBS\SacEnhancer\Formatter\FormatterInterface;

class ToArrayFormatter implements FormatterInterface
{
    public function format($response, array $queryArgs = [])
    {
        return ObjectHelper::toArray($response);
    }
}
