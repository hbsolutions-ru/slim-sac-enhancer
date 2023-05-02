<?php declare(strict_types=1);

namespace HBS\SacEnhancer\Formatter\Objects;

use DateTime;
use DateTimeImmutable;

class DateTimeFormatter extends BaseFormatter
{
    protected string $format;

    public function __construct(string $format)
    {
        $this->format = $format;
    }

    protected function formatObject($response)
    {
        if (!(
            ($response instanceof DateTime) ||
            ($response instanceof DateTimeImmutable)
        )) {
            return $response;
        }

        return $response->format($this->format);
    }
}
