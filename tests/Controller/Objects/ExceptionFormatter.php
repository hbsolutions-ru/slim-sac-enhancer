<?php declare(strict_types=1);

namespace Tests\Controller\Objects;

use Throwable;
use HBS\SacEnhancer\Formatter\Throwable\BaseThrowableFormatter;

final class ExceptionFormatter extends BaseThrowableFormatter
{
    protected function formatThrowable(Throwable $throwable): array
    {
        return [
            'status' => 'fail',
            'errors' => [
                [
                    'code' => $throwable->getCode(),
                    'message' => $throwable->getMessage(),
                ],
            ],
        ];
    }
}
