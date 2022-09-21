<?php declare(strict_types=1);

namespace Tests\Formatter\Arrays;

use PHPUnit\Framework\TestCase;
use HBS\Helpers\StringHelper;
use HBS\SacEnhancer\Formatter\Arrays\PaginationFormatter;

final class PaginationFormatterTest extends TestCase
{
    private function getData(int $size = 20): array
    {
        $data = [];

        for ($i = 1; $i <= $size; $i++) {
            $data[] = [
                'id' => $i,
                'name' => StringHelper::randomBase62(8),
            ];
        }

        return $data;
    }

    public function testFormat(): void
    {
        $limit = 5;
        $offset = 5;
        $total = 20;

        $formatter = new PaginationFormatter();

        $result = $formatter->format(
            $this->getData($total),
            [
                PaginationFormatter::LIMIT => $limit,
                PaginationFormatter::OFFSET => $offset,
            ]
        );

        $this->assertIsArray($result);
        $this->assertCount(4, $result);

        $this->assertArrayHasKey(PaginationFormatter::DATA, $result);
        $this->assertArrayHasKey(PaginationFormatter::LIMIT, $result);
        $this->assertArrayHasKey(PaginationFormatter::OFFSET, $result);
        $this->assertArrayHasKey(PaginationFormatter::TOTAL, $result);

        $this->assertIsArray($result[PaginationFormatter::DATA]);
        $this->assertEquals($limit, $result[PaginationFormatter::LIMIT]);
        $this->assertEquals($offset, $result[PaginationFormatter::OFFSET]);
        $this->assertEquals($total, $result[PaginationFormatter::TOTAL]);

        $this->assertCount($limit, $result[PaginationFormatter::DATA]);
        $this->assertEquals($offset + 1, $result[PaginationFormatter::DATA][0]['id']);
    }
}
