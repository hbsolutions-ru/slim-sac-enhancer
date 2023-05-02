<?php declare(strict_types=1);

namespace Tests\Formatter\Objects;

use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Formatter\Objects\DateTimeFormatter;

final class DateTimeFormatterTest extends TestCase
{
    public function testFormatDateTime(): void
    {
        $dateTimeFormat = "Y-m-d H:i:s";

        $dateTime = new DateTime();
        $expectedValue = $dateTime->format($dateTimeFormat);

        $formatter = new DateTimeFormatter($dateTimeFormat);

        $result = $formatter->format($dateTime);

        $this->assertEquals($expectedValue, $result);
    }

    public function testFormatDateTimeImmutable(): void
    {
        $dateTimeFormat = "Y-m-d H:i:s";

        $dateTimeImmutable = new DateTimeImmutable();
        $expectedValue = $dateTimeImmutable->format($dateTimeFormat);

        $formatter = new DateTimeFormatter($dateTimeFormat);

        $result = $formatter->format($dateTimeImmutable);

        $this->assertEquals($expectedValue, $result);
    }
}
