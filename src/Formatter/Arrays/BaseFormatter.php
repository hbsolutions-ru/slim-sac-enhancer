<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Arrays;

use HBS\SacEnhancer\Exception\UnexpectedValueException;
use HBS\SacEnhancer\Formatter\FormatterInterface;

abstract class BaseFormatter implements FormatterInterface
{
    abstract protected function formatArray(array $response, $queryArgs = []);

    public function format($response, $queryArgs = [])
    {
        $this->validate($response);
        return $this->formatArray($response, $queryArgs);
    }

    protected function validate($response): void
    {
        if (!is_array($response)) {
            throw new UnexpectedValueException(sprintf(
                "Formatter argument must be of type array, %s given",
                gettype($response)
            ));
        }
    }
}
