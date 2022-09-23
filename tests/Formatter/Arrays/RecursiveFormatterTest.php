<?php declare(strict_types=1);

namespace Tests\Formatter\Arrays;

use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Formatter\Arrays\{
    FilterNullsFormatter,
    RecursiveFormatter,
};

final class RecursiveFormatterTest extends TestCase
{
    public function testFormat()
    {
        $innerFormatter = new FilterNullsFormatter();

        $formatter = new RecursiveFormatter($innerFormatter);

        $result = $formatter->format([
            'integerField' => 42,
            'nullField' => null,
            'inner1' => [
                'emptyStringField' => "",
                'nullField' => null,
            ],
            'inner2' => [
                'stringField' => "Hello World!",
                'nullField' => null,
                'arrayField' => [
                    'nullField' => null,
                ],
            ]
        ]);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);
        $this->assertArrayNotHasKey("nullField", $result);
        $this->assertArrayHasKey("integerField", $result);
        $this->assertArrayHasKey("inner1", $result);
        $this->assertArrayHasKey("inner2", $result);

        $this->assertIsArray($result['inner1']);
        $this->assertCount(1, $result['inner1']);
        $this->assertArrayNotHasKey("nullField", $result['inner1']);
        $this->assertArrayHasKey("emptyStringField", $result['inner1']);

        $this->assertIsArray($result['inner2']);
        $this->assertCount(2, $result['inner2']);
        $this->assertArrayNotHasKey("nullField", $result['inner2']);
        $this->assertArrayHasKey("stringField", $result['inner2']);
        $this->assertArrayHasKey("arrayField", $result['inner2']);

        $this->assertIsArray($result['inner2']['arrayField']);
        $this->assertCount(0, $result['inner2']['arrayField']);
    }
}
