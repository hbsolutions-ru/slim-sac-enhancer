<?php declare(strict_types=1);

namespace Tests\Formatter\Arrays;

use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Formatter\Arrays\FilterNullsFormatter;

final class FilterNullsFormatterTest extends TestCase
{
    public function testFormatArray(): void
    {
        $formatter = new FilterNullsFormatter();

        $result = $formatter->format([
            'arrayField' => [],
            'emptyStringField' => "",
            'integerField' => 42,
            'nullField' => null,
            'stringField' => "Hello World!",
            'zeroField' => 0,
        ]);

        $this->assertIsArray($result);
        $this->assertCount(5, $result);
        $this->assertArrayNotHasKey('nullField', $result);

        $this->assertArrayHasKey('arrayField', $result);
        $this->assertArrayHasKey('emptyStringField', $result);
        $this->assertArrayHasKey('integerField', $result);
        $this->assertArrayHasKey('stringField', $result);
        $this->assertArrayHasKey('zeroField', $result);

        $this->assertEquals([], $result['arrayField']);
        $this->assertEquals("", $result['emptyStringField']);
        $this->assertEquals(42, $result['integerField']);
        $this->assertEquals("Hello World!", $result['stringField']);
        $this->assertEquals(0, $result['zeroField']);
    }
}
