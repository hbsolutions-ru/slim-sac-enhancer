<?php declare(strict_types=1);

namespace HBS\SacEnhancer\FormatterFactory;

use Psr\Http\Message\ServerRequestInterface as Request;
use HBS\SacEnhancer\Formatter\FormatterInterface;

interface FormatterFactoryInterface
{
    /**
     * @param Request $request
     * @param string $fallbackFormatter
     * @return FormatterInterface
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \HBS\SacEnhancer\Exception\ExceptionInterface
     */
    public function get(Request $request, string $fallbackFormatter): FormatterInterface;
}
