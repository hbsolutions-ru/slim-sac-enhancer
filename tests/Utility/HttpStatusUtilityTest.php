<?php declare(strict_types=1);

namespace Tests\Utility;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use HBS\SacEnhancer\Utility\HttpStatusUtility;

final class HttpStatusUtilityTest extends TestCase
{
    public function testFilterStatusPositiveCases(): void
    {
        $expectedValue = 200;
        $result = HttpStatusUtility::filterStatus($expectedValue);
        $this->assertEquals($expectedValue, $result);

        $expectedValue = 404;
        $result = HttpStatusUtility::filterStatus($expectedValue);
        $this->assertEquals($expectedValue, $result);
    }

    public function testFilterStatusNegativeCases(): void
    {
        try {
            HttpStatusUtility::filterStatus(99);
            $this->fail("Exception not thrown but expected");
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('Invalid HTTP status code.', $e->getMessage());
        }

        try {
            HttpStatusUtility::filterStatus(600);
            $this->fail("Exception not thrown but expected");
        } catch (InvalidArgumentException $e) {
            $this->assertEquals('Invalid HTTP status code.', $e->getMessage());
        }
    }
}
