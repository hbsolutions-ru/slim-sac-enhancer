<?php declare(strict_types=1);

namespace Tests\Formatter\Objects;

use DateTime;
use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Formatter\Objects\DateTimeFormatter;

final class DateTimeFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $dateTimeFormat = "Y-m-d H:i:s";

        $dateTime = new DateTime();
        $expectedValue = $dateTime->format($dateTimeFormat);

        $formatter = new DateTimeFormatter($dateTimeFormat);

        $result = $formatter->format($dateTime);

        $this->assertEquals($expectedValue, $result);
    }
}
