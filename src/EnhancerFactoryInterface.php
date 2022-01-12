<?php declare(strict_types=1);

namespace HBS\SacEnhancer;

use Psr\Http\Message\ServerRequestInterface as Request;

interface EnhancerFactoryInterface
{
    /**
     * @param string $type
     * @param Request $request
     * @param string $namePattern
     * @param string $fallback
     *
     * @return EnhancerInterface
     *
     * @throws \DI\DependencyException
     * @throws \DI\NotFoundException
     * @throws \HBS\SacEnhancer\Exception\ExceptionInterface
     */
    public function get(string $type, Request $request, string $namePattern, string $fallback = null): EnhancerInterface;
}
