<?php declare(strict_types=1);

namespace Tests\Formatter\Throwable;

use RuntimeException;

use PHPUnit\Framework\TestCase;
use Tests\Objects\ExceptionFormatter;

final class BaseThrowableFormatterTest extends TestCase
{
    public function testFormat(): void
    {
        $httpResponseCode = 404;
        $errorMessage = "Page not found";

        $exception = new RuntimeException($errorMessage, $httpResponseCode);
        $formatter = new ExceptionFormatter();

        $result = $formatter->format($exception);

        $this->assertIsArray($result);

        $this->assertArrayHasKey("status", $result);
        $this->assertEquals("fail", $result['status']);

        $this->assertArrayHasKey("errors", $result);
        $this->assertIsArray($result['errors']);
        $this->assertArrayHasKey(0, $result['errors']);

        $errorData = $result['errors'][0];

        $this->assertArrayHasKey("code", $errorData);
        $this->assertEquals($httpResponseCode, $errorData['code']);

        $this->assertArrayHasKey("message", $errorData);
        $this->assertEquals($errorMessage, $errorData['message']);

    }
}
