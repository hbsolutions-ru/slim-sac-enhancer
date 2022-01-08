<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter;

use HBS\SacEnhancer\EnhancerInterface;

interface FormatterInterface extends EnhancerInterface
{
    public function format($response);
}
