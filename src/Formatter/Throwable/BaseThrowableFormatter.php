<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Throwable;

use Throwable;
use HBS\SacEnhancer\Formatter\FormatterInterface;

abstract class BaseThrowableFormatter implements FormatterInterface
{
    abstract protected function formatThrowable(Throwable $throwable): array;

    public function format($response, array $queryArgs = [])
    {
        if (!($response instanceof Throwable)) {
            return null;
        }

        return $this->formatThrowable($response);
    }
}
