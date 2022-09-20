<?php declare(strict_types=1);

namespace Tests\Formatter\Objects;

use stdClass;
use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Formatter\Objects\ToArrayFormatter;

final class ToArrayFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $stdObject = new stdClass();
        $stdObject->integerField = 42;
        $stdObject->stringField = "Hello World!";

        $formatter = new ToArrayFormatter();

        $result = $formatter->format($stdObject);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        $this->assertArrayHasKey('integerField', $result);
        $this->assertArrayHasKey('stringField', $result);

        $this->assertEquals(42, $result['integerField']);
        $this->assertEquals("Hello World!", $result['stringField']);
    }
}
