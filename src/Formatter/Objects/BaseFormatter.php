<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Objects;

use HBS\SacEnhancer\Exception\UnexpectedValueException;
use HBS\SacEnhancer\Formatter\FormatterInterface;

abstract class BaseFormatter implements FormatterInterface
{
    abstract protected function formatObject($response);

    public function format($response, $queryArgs = [])
    {
        $this->validate($response);
        return $this->formatObject($response);
    }

    protected function validate($response): void
    {
        if (!is_object($response)) {
            throw new UnexpectedValueException(sprintf(
                "Formatter argument must be of type object, %s given",
                gettype($response)
            ));
        }
    }
}
